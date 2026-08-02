<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# 英語自己学習用アプリ 要件定義書

---

# 1. プロジェクト概要

## サービス名

**VocaReview**

## 目的

- 現在自己学習で使用しているスプレッドシートをWebアプリへ移行し、動作を軽快にする。
- 英単語・フレーズ・文法を効率よく登録・管理・復習できる環境を構築する。
- 自分専用の英語学習ツールとして、学習効率を向上させる。

## 解決したい課題

- スプレッドシートの動作が重い。
- 単語や文法の追加・編集・検索がしづらい。
- 覚えていない内容だけを簡単に復習できない。
- 学習記録を残せない。

## ターゲットユーザー

自分（ログイン・ユーザー登録機能なし）

---

# 2. 機能要件

## 機能1：一覧表示

登録した単語・フレーズ・文法を一覧表示する。

### 単語・フレーズ

表示項目

- 英単語・フレーズ
- 品詞
- 意味
- 例文（英語）
- 例文（日本語）
- 覚えた／未習得

### 文法（Grammar）

表示項目

- 文法名
- 解説
- 例文（英語）
- 例文（日本語）
- 覚えた／未習得

### 一覧画面でできること

- 検索
- 並び替え
- ページネーション
- 編集
- 削除

---

## 機能2：登録機能

### 単語・フレーズ

登録できる項目

- 英単語・フレーズ
- 品詞
- 意味
- 例文（英語）
- 例文（日本語）

※ 品詞はプルダウンから選択する。

例

- Noun
- Verb
- Adjective
- Adverb
- Pronoun
- Preposition
- Conjunction
- Interjection

### 文法（Grammar）

登録できる項目

- 文法名
- 解説
- 例文（英語）
- 例文（日本語）

---

## 機能3：編集・削除機能

登録したデータを編集・削除できる。

---

## 機能4：復習機能

以下の条件で絞り込み表示できる。

- 全件
- 覚えた
- 覚えていない

---

## 機能5：学習記録

1日に100項目（単語・フレーズ・文法）を復習したら学習記録を保存する。

表示内容

- 今日の復習数
- 連続学習日数
- 総復習回数
- 最終学習日

---

# 3. 非機能要件

- Laravel
- Blade
- Tailwind CSS
- MySQL
- JavaScript（必要最低限）
- レスポンシブ対応（PC・スマートフォン）

---

# 4. 画面設計

ログイン機能は実装しない。

すべての機能をトップページに集約する。

## 画面構成

1. ヘッダー（アプリ名・学習記録）
2. 検索・絞り込みエリア
3. 登録フォーム
4. 一覧表示テーブル
5. ページネーション
6. フッター

---

# 5. データ設計

Laravel Eloquentを利用した設計とする。

実装時にMigration・Modelを作成する。

### 単語・フレーズ

- 英単語・フレーズ
- 品詞
- 意味
- 例文（英語）
- 例文（日本語）
- 覚えたかどうか
- 作成日時
- 更新日時

### 文法

- 文法名
- 解説
- 例文（英語）
- 例文（日本語）
- 覚えたかどうか
- 作成日時
- 更新日時

### 学習記録

- 学習日
- 復習数
- 作成日時
- 更新日時

---

# 6. ルーティング設計（Laravel）

LaravelのRESTfulルーティングに沿って実装する。

単語・フレーズ、文法、学習記録それぞれにCRUDを実装する。

---

# 7. 入力・出力仕様

## 入力

### 単語・フレーズ

- 英単語・フレーズ
- 品詞
- 意味
- 例文（英語）
- 例文（日本語）

### 文法

- 文法名
- 解説
- 例文（英語）
- 例文（日本語）

---

## 出力

一覧表示

### 単語・フレーズ

- 英単語・フレーズ
- 品詞
- 意味
- 例文（英語）
- 例文（日本語）
- 覚えた状態

### 文法

- 文法名
- 解説
- 例文（英語）
- 例文（日本語）
- 覚えた状態

---

# 8. バリデーション

- 必須項目は未入力で登録できないようにする。
- 文字数制限などの詳細は実装時に設定する。

---

# 9. UI / UX 方針

## コンセプト

**学習に集中できるシンプルなデザイン**

## デザイン

- メインカラー：青系
- アクセントカラー：緑系
- 背景色：クリーム系
- 文字色：ダークグレー

## レイアウト

- 余白を十分に確保する。
- シンプルで見やすいデザインとする。
- 直感的に操作できるUIとする。
- 長時間学習しても目が疲れにくい配色とする。

---

# 10. 今後の機能追加について

本アプリは自己学習用として運用し、使用しながら必要だと感じた機能を適宜追加していくものとする。

---

# 11. 開発環境

- Laravel
- PHP
- MySQL
- Blade
- Tailwind CSS
- JavaScript
- Vite
- Git / GitHub
- Visual Studio Code

---

# 12. 開発スケジュール

1. プロジェクト作成
2. データベース設計
3. Migration作成
4. Model作成
5. Controller作成
6. ルーティング設定
7. 一覧表示機能
8. 登録機能
9. 編集機能
10. 削除機能
11. 検索・絞り込み機能
12. 復習機能
13. 学習記録機能
14. UIデザイン調整
15. レスポンシブ対応
16. 動作確認・リファクタリング
