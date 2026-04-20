# Be Framework スケルトン

[Be Framework](https://be-framework.github.io/) のプロジェクトスケルトン。

## はじめに

```bash
composer create-project be-framework/skeleton MyProject
cd MyProject
```

## 使い方

```bash
composer dev      # セマンティックログ付きで実行 → var/log/<timestamp>.json
composer stree    # 最新ログをツリー表示
composer app      # 本番モードで実行（ログなし）

# 直接呼び出し（BEAR.Sunday スタイルの URI）
php bin/app.php 'hello?name=Alice'
MODULE=app php bin/app.php 'order?customerId=42&items[]=P1001'
```

開発ループ、`MODULE` 環境変数、URI 呼び出し規約は `CLAUDE.md` を参照。

## ディレクトリ構成

スケルトンには `src/<dir>/` が10個ありますが、3つは必要になるまで意図的に空にしてあります。各行は [Be Framework マニュアル](https://be-framework.github.io/manuals/1.0/ja/) の対応する章にリンクしています。

| dir | 役割 | マニュアル |
|---|---|---|
| `src/Input/`      | パイプラインの起点。`#[Be([Target::class])]` で次段を宣言。                                | [Input クラス](https://be-framework.github.io/manuals/1.0/ja/02-input-classes.html) |
| `src/Final/`      | 終点。`#[Input]` でデータ、`#[Inject]` でサービスを受け取る。                              | [Final オブジェクト](https://be-framework.github.io/manuals/1.0/ja/04-final-objects.html) |
| `src/Semantic/`   | セマンティック変数（バリデータ）。クラス名 = パラメータ名（camelCase）。                       | [セマンティック変数](https://be-framework.github.io/manuals/1.0/ja/06-semantic-variables.html) |
| `src/Exception/`  | セマンティック検証例外。`#[Message]` で多言語化。                                             | [エラーハンドリング](https://be-framework.github.io/manuals/1.0/ja/09-error-handling.html) |
| `src/Reason/`     | 「存在を可能にするもの」— Entity、Media（Command/Query）、ポリシー、ガード。                  | [Reason レイヤー](https://be-framework.github.io/manuals/1.0/ja/08-reason-layer.html) |
| `src/Module/`     | Ray.Di モジュール。`MODULE=<name>` で有効モジュールを切り替え。                                | （スケルトン固有 — `CLAUDE.md` 参照） |
| `src/Becoming/`   | フレームワーク配線層。ユーザーコードを置く場所ではない。                                       | [Becoming](https://be-framework.github.io/manuals/1.0/ja/04a-becoming.html) |
| `src/Being/`      | *(空)* `$being` 判別子 + `#[Be([FinalA, ...])]` を使う Branching 中間形。                     | [Being クラス](https://be-framework.github.io/manuals/1.0/ja/03-being-classes.html) |
| `src/LogContext/` | *(空)* `Been` に添えるセマンティックログのイベントクラス。                                     | [セマンティックロギング](https://be-framework.github.io/manuals/1.0/ja/10-semantic-logging.html) |
| `src/Moment/`     | *(空)* Diamond の部分 — `MomentInterface` を実装し、`be()` で潜在性を実現。                   | [メタモルフォーシスパターン](https://be-framework.github.io/manuals/1.0/ja/05-metamorphosis-patterns.html) |

## 名前空間

`Be\Skeleton` は AI ツールまたは find-and-replace で自分のプロジェクトの名前空間に置き換えてください。

## もっと知る

- [Be Framework ドキュメント](https://be-framework.github.io/)
- [be-skills](https://github.com/be-framework/be-skills) — Be Framework アプリを end-to-end で作るための Claude Code スキル（プロジェクト設定、設計ワークフロー、開発ループ、デバッグ）。
- [be-patterns](https://github.com/be-framework/be-patterns) — 8つの実行可能なパターンデモ（Linear、Diamond、Branching、Cascade Diamond、Complex Convergence、…）。

---

[English version](./README.md)
