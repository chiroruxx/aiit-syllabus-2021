# AIIT Syllabus 2021
## 概要
AIIT のシラバスをweb化して見やすくするプロジェクト。

IssueもPRも大歓迎。

## 開発環境の作り方
以下のソフトウェアが必要です。

- docker
- docker-compose
- composer

事前にインストールをしておいてください。

```sh
cp -a .env.example .env
composer install
./vendor/bin/sail up
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

これで http://localhost にアクセスすると見られると思います。
見れなかったら Issue を立ててくれると！
