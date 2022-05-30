=== 001 Prime Strategy Translate Accelerator ===
Contributors: primestrategy, Kengyu Nakamura
Tags: translate, speed up
Requires at least: 2.9
Tested up to: 4.4.2
Stable tag: 1.1.1

翻訳ファイルの読み込みをキャッシュ化、もしくは読み込み自体を停止し、表示までにかかる実行時間を短縮します。APCがインストールされている環境では、APCのユーザーキャッシュ機構を利用して、ファイルキャッシュよりも高速に動作させることも可能です。

== Description ==
翻訳ファイルの読み込みをキャッシュ化、もしくは読み込み自体を停止し、表示までにかかる実行時間を短縮します。APCがインストールされている環境では、APCのユーザーキャッシュ機構を利用して、ファイルキャッシュよりも高速に動作させることも可能です。


= Functions =
1. 翻訳ファイルをキャッシュ化し読み込みにかかる時間を縮小、表示までにかかる実行時間を短縮します。
2. APCがインストールされている環境においては、APCのキャッシュ機能を利用することも出来、さらに高速に動作します。

== Installation ==

1. "001-prime-strategy-translate-accelerator" フォルダをpluginsディレクトリにアップロードしてください。
2. キャッシュディレクトリに書き込み権限を与えてください。デフォルトのキャッシュディレクトリは、001-prime-strategy-translate-accelerator/cacheとなります。
3. プラグインメニューから、"001 Prime Strategy Translate Accelerator"を有効化してください。
4. 設定のTranslate Acceleratorメニューから、翻訳キャッシュの各種設定を行って下さい。

== Changelog ==
= 1.1.1 =
* wp-content/languages/themes と plugins ディレクトリのmoファイルに対応
= 1.1.0 =
* WordPress 4.1.1 対応
= 1.0.9 =
* WordPress 3.6 対応
* ルーマニア語追加
= 1.0.8 =
* WordPress 3.4 対応
= 1.0.7 =
* 英語訳を修正
= 1.0.6 =
* 変数名の間違いを修正
= 1.0.3 =
* Public release

== Screenshots ==
1. 設定ページ
2. 翻訳キャッシュによる効果表
