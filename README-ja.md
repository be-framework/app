README# Be Framework コンテンツ管理システム

## 概要

このプロジェクトは**Be Framework**を使用した本格的なコンテンツ管理システム（CMS）の実装例です。Be Frameworkの核となる「**オントロジカル・プログラミング**」パラダイムを実証し、従来の「アクション志向」から「存在志向」への根本的な転換を示しています。

## Be Framework の核心思想

### 「BE」 vs 「DO」の哲学

```php
// 従来のアプローチ (DO - アクション志向)
$content->validate();
$content->checkPermissions(); 
$content->publish();

// Be Framework のアプローチ (BE - 存在志向) 
$result = $becoming($contentInput); // オブジェクトが「何になるか」を決定
```

Be Frameworkでは、オブジェクトは**行動する**のではなく**変化する**ことで状態を表現します。

## アーキテクチャ概要

### メタモルフォシス・チェーン（変身連鎖）

```
ContentCreationInput
    ↓ [セマンティック検証]
UnvalidatedContent  
    ↓ [検証完了]
ValidatedContent
    ↓ [Reasonサービス統合]
ProcessedContent
    ↓ [ワークフロー決定]
PublishedContent | DraftContent | PendingReviewContent | RejectedContent
```

各段階で、オブジェクトは自身の**存在状態**を次の段階へと変化させます。

## ディレクトリ構造

```
src/
├── Input/           # エントリーポイント（入力の型定義）
├── Being/           # 存在状態のクラス（メタモルフォシス段階）
├── Reason/          # オントロジカル・サービス（推論エンジン）
├── Semantic/        # セマンティック検証（意味論的妥当性）
├── Exception/       # ドメイン例外（具体的エラー情報）
└── Module/          # 依存性注入設定
```

## 1. Input Classes - エントリーポイント

```php
#[Be([UnvalidatedContent::class])]
final readonly class ContentCreationInput
{
    public function __construct(
        public string $title,
        public string $body,
        public string $email,
        public string $category,
        public array $tags = [],
        public ?string $publishDate = null,
        public string $userRole = 'contributor'
    ) {}
}
```

**特徴:**
- `#[Be]` 属性で次の変身先を宣言
- `readonly` でイミュータブル性を保証
- 単純なデータ受け取りのみ（ロジックなし）

## 2. Being Classes - 存在状態の表現

### UnvalidatedContent（未検証状態）

```php
#[Be([ValidatedContent::class])]
final readonly class UnvalidatedContent
{
    // セマンティック検証を受ける状態
}
```

### ValidatedContent（検証済み状態）

```php
#[Be([ProcessedContent::class])]
final readonly class ValidatedContent
{
    // 検証完了、ビジネスロジック処理待ち状態
}
```

### ProcessedContent（処理決定状態）

```php
#[Be([PublishedContent::class, DraftContent::class, PendingReviewContent::class, RejectedContent::class])]
final readonly class ProcessedContent
{
    public PublishedContent|DraftContent|PendingReviewContent|RejectedContent $being;

    public function __construct(
        #[Input] public string $title,
        // ... 他のパラメータ
        #[Inject] ContentWorkflowDecision $workflowDecision
    ) {
        $decision = $workflowDecision->determineWorkflowAction(
            $this->title, $this->body, $this->email,
            $this->category, $this->tags, $this->publishDate, $this->userRole
        );

        $this->being = match ($decision['action']) {
            'publish_immediately' => new PublishedContent(...),
            'save_as_draft', 'scheduled' => new DraftContent(...),
            'pending_security_review' => new PendingReviewContent(...),
            'blocked', 'rejected' => new RejectedContent(...)
        };
    }
}
```

**重要なポイント:**
- コンストラクタ内で**Reasonサービス**を呼び出し
- `match` 式で**型駆動の自己決定**を実装
- 結果として最適な存在状態に変身

## 3. Reason Services - オントロジカル推論エンジン

### Reasonサービスの階層構造

```php
ContentWorkflowDecision (統合オーケストレーター)
├── PublicationDecision (時間的判定)
├── ContentQualityAssessment (品質評価) 
├── SecurityPolicyEnforcement (セキュリティ判定)
└── UserRoleAuthorization (権限判定)
```

### ContentWorkflowDecision - 統合推論サービス

```php
final readonly class ContentWorkflowDecision
{
    public function __construct(
        private PublicationDecision $publicationDecision,
        private ContentQualityAssessment $qualityAssessment,
        private SecurityPolicyEnforcement $securityPolicy,
        private UserRoleAuthorization $roleAuthorization
    ) {}

    public function determineWorkflowAction(...): array
    {
        // 1. セキュリティチェック（最優先）
        $securityAssessment = $this->securityPolicy->evaluateContentSecurity(...);
        if ($this->securityPolicy->shouldBlockContent($securityAssessment)) {
            return ['action' => 'blocked', 'reason' => '...'];
        }

        // 2. 品質評価
        $qualityAssessment = $this->qualityAssessment->assessContentQuality(...);
        
        // 3. 権限確認  
        $canPublish = $this->roleAuthorization->canUserPerformAction($userRole, 'publish');
        
        // 4. 公開タイミング判定
        $shouldPublishNow = $this->publicationDecision->shouldPublish($publishDate);

        // 5. 統合判定ロジック
        // ... 複雑な条件分岐による最終決定
    }
}
```

### UserRoleAuthorization - 階層的権限管理

```php
final readonly class UserRoleAuthorization
{
    private array $roleHierarchy = [
        'admin' => ['editor', 'contributor', 'subscriber'],
        'editor' => ['contributor', 'subscriber'], 
        'contributor' => ['subscriber'],
        'subscriber' => []
    ];

    public function canUserPerformAction(string $userRole, string $action): bool
    {
        // 直接権限チェック
        if (in_array($action, $this->permissions[$userRole], true)) {
            return true;
        }

        // 継承権限チェック（再帰的）
        if (isset($this->roleHierarchy[$userRole])) {
            foreach ($this->roleHierarchy[$userRole] as $inheritedRole) {
                if ($this->canUserPerformAction($inheritedRole, $action)) {
                    return true;
                }
            }
        }

        return false;
    }
}
```

### ContentQualityAssessment - 包括的品質評価

```php
final readonly class ContentQualityAssessment
{
    public function assessContentQuality(string $title, string $body, string $category, array $tags): array
    {
        $score = 100;
        $issues = [];
        $recommendations = [];

        // タイトル評価
        if (strlen(trim($title)) < 10) {
            $score -= 15;
            $issues[] = "Title too short";
            $recommendations[] = "Expand title to be more descriptive";
        }

        // コンテンツ評価  
        $wordCount = str_word_count($body);
        if (strlen(trim($body)) < 100) {
            $score -= 25;
            $issues[] = "Content too short";
        }

        // カテゴリ固有要件
        if ($this->checkCategoryRequirements($body, $category)) {
            // ...
        }

        return [
            'score' => max(0, $score),
            'grade' => $this->getQualityGrade($score),
            'issues' => $issues,
            'recommendations' => $recommendations,
            'reading_time_minutes' => ceil($wordCount / 200),
            'word_count' => $wordCount
        ];
    }
}
```

## 4. Semantic Validation - 意味論的検証

### セマンティック変数のパターン

```php
final class Email
{
    #[Validate]
    public function validate(string $email): void
    {
        if (empty(trim($email))) {
            throw new InvalidEmailException($email, 'cannot be empty');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException($email, 'invalid format');
        }
        
        if (strlen($email) > 254) {
            throw new InvalidEmailException($email, 'too long (max 254 characters)');
        }
    }
}
```

### 具体的例外クラスのパターン

#### 従来の曖昧な例外
```php
// ❌ 曖昧で情報不足
throw new Exception('Invalid email');
```

#### Be Framework の具体的例外
```php
// ✅ 具体的で多言語対応
#[Message([
    'en' => 'Invalid email address "{email}": {reason}',
    'ja' => '無効なメールアドレス"{email}": {reason}'
])]
final class InvalidEmailException extends DomainException
{
    public function __construct(
        public readonly string $email,
        public readonly string $reason
    ) {
        parent::__construct("Invalid email address \"{$email}\": {$reason}");
    }
}
```

### セマンティック例外の責任分離

```php
// LastName.php - シンプルな検証ロジック
final class LastName
{
    #[Validate] 
    public function validate(string $lastName): void
    {
        $trimmed = trim($lastName);
        
        if (empty($trimmed)) {
            throw new EmptyLastNameException($lastName);      // 具体的例外
        }
        
        if (strlen($trimmed) < 2) {
            throw new LastNameTooShortException($lastName);   // 具体的例外
        }
        
        if (strlen($trimmed) > 50) {
            throw new LastNameTooLongException($lastName);    // 具体的例外
        }
        
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\'\.]+$/u', $trimmed)) {
            throw new LastNameInvalidCharactersException($lastName); // 具体的例外
        }
    }
}

// EmptyLastNameException.php - 例外クラスがメッセージを管理
#[Message([
    'en' => 'Last name "{lastName}" cannot be empty',
    'ja' => '姓"{lastName}"は空にできません'
])]
final class EmptyLastNameException extends DomainException
{
    public function __construct(public readonly string $lastName) {
        parent::__construct("Last name \"{$lastName}\" cannot be empty");
    }
}
```

## 5. 依存性注入設定

```php
// Module/AppModule.php
final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Reasonサービスを自動注入対象として登録
        $this->bind(PublicationDecision::class);
        $this->bind(ContentQualityAssessment::class);
        $this->bind(SecurityPolicyEnforcement::class);
        $this->bind(UserRoleAuthorization::class);
        $this->bind(ContentWorkflowDecision::class);
    }
}
```

## 実行例

### 基本的な使用方法

```php
use Be\Framework\Becoming;
use Ray\Di\Injector;

$injector = new Injector(new AppModule());
$becoming = new Becoming($injector, 'Be\\App\\Semantic');

// エディター権限での即座公開
$editorInput = new ContentCreationInput(
    title: "Be Framework: 革新的PHPプログラミング",
    body: "Be Frameworkはオントロジカル・プログラミングを実現...",
    email: "editor@example.com",
    category: "technology", 
    tags: ["php", "framework"],
    userRole: "editor"
);

$result = $becoming($editorInput);
// 結果: PublishedContent（即座に公開）

// コントリビューター権限での品質不足コンテンツ
$contributorInput = new ContentCreationInput(
    title: "短いタイトル",
    body: "短すぎる内容。",
    email: "contributor@example.com", 
    category: "technology",
    tags: ["test"],
    userRole: "contributor"
);

$result = $becoming($contributorInput);
// 結果: DraftContent（ドラフトとして保存）
```

### ワークフロー決定の例

```php
// Reasonサービスによる統合判定例

// 1. セキュリティリスク検出
$suspiciousInput = new ContentCreationInput(
    title: "Click here for free money!",
    body: "Download malware now...",
    // ...
);
// 結果: RejectedContent（セキュリティ違反）

// 2. 権限不足
$subscriberInput = new ContentCreationInput(
    title: "高品質コンテンツ",
    body: "詳細で有用な長文コンテンツ...",
    userRole: "subscriber" // 公開権限なし
);
// 結果: RejectedContent（権限不足）

// 3. 手動レビュー要求
$reviewRequiredInput = new ContentCreationInput(
    title: "センシティブなトピック",
    body: "議論を呼ぶ可能性のある内容...",
    // ...
);
// 結果: PendingReviewContent（手動レビュー待ち）
```

## Be Framework の設計原則

### 1. Constructor-Driven Metamorphosis（コンストラクタ駆動変身）
- すべてのビジネスロジックはコンストラクタ内で実行
- メソッドは状態変更に使用しない
- オブジェクトは「生成と同時に完全な状態」になる

### 2. Type-Driven Self-Determination（型駆動自己決定）
- Union型 (`PublishedContent|DraftContent|...`) で可能な結果を宣言
- `match` 式でビジネスルールに基づいて具体的な型を選択
- 実行時の条件によって「何になるか」が決まる

### 3. Ontological Services（オントロジカル・サービス）  
- ビジネスロジックは「サービス」ではなく「推論エンジン」
- 複数のReasonサービスを組み合わせて高次の判定を行う
- ドメインの「存在論」を表現する

### 4. Semantic Validation（セマンティック検証）
- 値の「意味論的正しさ」を検証
- 具体的で情報豊富な例外クラス  
- 多言語対応エラーメッセージ

### 5. Immutable State Transitions（不変状態遷移）
- すべてのプロパティが `readonly`
- 状態変更は新しいオブジェクトの生成として表現
- 副作用のない純粋な変換

## 従来アプローチとの比較

| 観点 | 従来のアプローチ | Be Framework |
|------|----------------|--------------|
| **パラダイム** | アクション志向（DO） | 存在志向（BE） |
| **状態管理** | ミュータブル、メソッド呼び出し | イミュータブル、コンストラクタ変身 |
| **ビジネスロジック** | サービスクラス、手続き的 | オントロジカルサービス、推論的 |
| **エラー処理** | 汎用例外、文字列メッセージ | 具体的例外、構造化データ |
| **型安全性** | 実行時エラー | コンパイル時 + 実行時検証 |
| **テスタビリティ** | モック、スタブが必要 | 純粋関数的、注入テスト |

## まとめ

このBe Framework CMSは、**オントロジカル・プログラミング**の真髄を示しています：

- **Objects don't DO, they BECOME** - オブジェクトは行動せず、変化する
- **Constructor-driven logic** - コンストラクタがすべての変換を担う  
- **Type-driven decisions** - 型システムがビジネスルールを表現
- **Composed reasoning** - 複数の推論エンジンによる統合判定
- **Semantic precision** - 意味論的に正確な検証と例外

これにより、従来の手続き的プログラミングでは実現困難だった、**自然で直感的、かつ堅牢なドメインモデル**が構築されています。

---

**Be Framework**: *存在することの美学をコードで表現する*
