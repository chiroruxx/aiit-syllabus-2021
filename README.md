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
```

これで http://localhost にアクセスすると見られると思います。
見れなかったら Issue を立ててくれると！

## DBデータの入れ方
[kintone](https://aiit.cybozu.com/k/820/exportRecord?view=7658#q&sort_0=f7283&order_0=DESC) からCSVファイルをダウンロードしてください。
いちど「すべてクリア」した後に「すべて追加」すると良いと思います。

ダウンロードしたファイルを storage/app/seeds.csv という名前で配置してください。
その後、

```sh
./vendor/bin/sail artisan db:migrate --seed
```

を実行すると、DBにデータが入ります。
