# ヒトノママ ─ Scavendawn

ゾンビに呑まれた街で、物資を漁り、仲間を選び、**人間のまま**夜明けの救助を待つ、ドット絵の見下ろし型サバイバルゲームです。ブラウザだけで動き、スマホではPWAとしてホーム画面に追加できます。

A top-down pixel-art zombie survival game for the browser. Scavenge, choose your companions, and stay human until the rescue helicopter arrives. Works on phones (installable as a PWA) and on PC.

- 公開ページ / Play: https://msnr-create.jp/
- リポジトリ / Repository: https://github.com/MSNR-Create/Scavendawn
- 言語 / Languages: 日本語・English（タイトル画面で切替。初回は端末の言語に合わせます）

## 特徴 / Features

- 通常20分・クイック5分、難易度3種、職業4種。セーブなし
- 人間性：選択で上下し、回復・視界・スコア倍率に影響。0でゾンビ化
- 仲間（性格・デバフ・指示）、装備と武器の耐久、車、立てこもり、昼夜とライト
- ゾンビ7種、突発イベント（物資投下・大群・救難信号）、救助ヘリでの脱出
- スマホ縦・横、PC（キーボード＋マウス）に対応。ゲーム内に「遊び方」あり

## 動かし方 / Running

ビルド不要の静的ファイルです。フォルダごとHTTPSのサーバーに置いてください。

```
index.html        ゲーム本体（HTML/CSS/JSを1ファイルに収録）
manifest.json     PWA設定
sw.js             Service Worker（オフライン用キャッシュ）
rank.php          ランキングAPI（任意。PHP 7.4以上、フォルダに書き込み権限が必要）
help/             「遊び方」のスクリーンショット（help/en/ は英語版）
fonts/            DotGothic16（SIL OFL 1.1）
icon-*.png        アプリアイコン
```

- `rank.php` を置かない場合、スコアは端末内にだけ保存されます。
- `rank.php` は初回アクセス時に `rank_secret.php`（署名用の鍵）と `rank_data.json`（記録）を同じフォルダに作ります。この2つは公開リポジトリに入れないでください（`.gitignore` 済み）。
- ローカルで試すだけなら `index.html` を直接開いても遊べます（PWAとオンラインランキングは無効）。
- `index.html` を更新したら、`sw.js` の先頭にあるキャッシュ名（`scavendawn-vNN`）の数字を上げてください。

## プライバシー / Privacy

ゲーム本体は広告・アクセス解析・Cookieを使いません。ランキング登録時のみ、ニックネームとスコアをサーバーへ送ります。詳しくは `docs/privacy-addendum.md` を参照してください。

## ライセンス / License

- コード、コードで描いたドット絵、合成音、スクリーンショット：**MIT License**（`LICENSE`）
- フォント DotGothic16：**SIL Open Font License 1.1**（`fonts/OFL.txt`、`THIRD_PARTY_NOTICES.md`）

### 改変・再配布について / Forks and modified versions

改変版・派生版は歓迎です。このゲームを軸にみんなで盛り上がれるよう、**ゲーム名「ヒトノママ／Scavendawn」はぜひ残してください。**

- MITライセンスの条件どおり、`LICENSE` の著作権表示と許諾表示は残してください（フォントは `fonts/OFL.txt` も）。
- お願い：公式版と区別がつくようにしてください。おすすめは、名前の後ろに版の名前を足す形です。
  - 例：`ヒトノママ ─ Scavendawn（○○版）` / `Scavendawn: ○○ Edition` / `Scavendawn [mod: ○○]`
  - タイトル画面や説明に「改変版（非公式）」である旨と、このリポジトリへのリンクを添えてください。
- 改変版のランキング、問い合わせ、情報の取り扱いは、公開した方の責任でお願いします。
- 作ったら、ぜひ Issue などで教えてください。

Forks and modified versions are welcome. **Please keep the name "ヒトノママ / Scavendawn"** so that everything built on this game can be found and enjoyed together.

- Keep the copyright and permission notice in `LICENSE` (and `fonts/OFL.txt` for the font), as the MIT License requires.
- Please make your version distinguishable from the official one. We suggest adding an edition name after the title, e.g. `Scavendawn: ○○ Edition` or `Scavendawn [mod: ○○]`, stating on the title screen or in the description that it is a modified (unofficial) version, and linking back to this repository.
- You are responsible for your own version's ranking, support and data handling.
- If you make one, let us know in an Issue!

制作 / Author: [MSNR_Create](https://msnr-create.jp/)

© 2026 MSNR_Create
