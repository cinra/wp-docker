# Dockerで作るWordpress環境

## 事前準備

1. Docker入れておいて！（`docker-machine`っていうコマンドが認識されていれば大丈夫） https://www.docker.com/products/docker-toolbox
1. `/etc/hosts`に`192.168.99.100 cinra.dev`を加えておいて！（ブラウザで`http://cinra.dev`にアクセスできるようになります）

※ https://github.com/itaoyuta/boozer

## 手順

### Docker Machineを立ち上げる

Docker Machineとは、仮想サーバーのインスタンスと同一と捉えてOKです。

```sh
$ docker-machine start
$ eval $(docker-machine env)
```

**解説**

**docker-machine start default**: Docker Machine（≒ VMインスタンス）を立ち上げる

**eval $(docker-machine env)**: 使用するDocker Machineを指定する

NOTE: `docker-machine env`を叩くと、様々な環境変数の設定項目が出力される。`$()`に入れてその出力結果を`eval`で実行しているだけ。

### Docker Imageを作成して、Containerを立ち上げる

```sh
$ docker-compose build
$ docker-compose up -d
```

**解説**

**docker-compose build**: Docker Composeを使って、イメージを作成している

- 何のイメージをどのように作成するかという設定は、[docker-compose.yml](https://github.com/cinra/wp-docker/blob/master/docker-compose.yml)で行っている。

**docker-compose up -d**: Docker Composeを使って、コンテナ（Container）を立ち上げている

- `-d`オプションは、バックグラウンドで動かすための指定
  - 試しに`-d`なしで立ち上げてみると、ログがバリバリ出てきて楽しいかも
- 「Kitematic」というGUIツール（docker-toolboxに付属）を使うと、今ローカルで立ち上がっているコンテナが確認できるので便利

---

## 詳説

### docker-compose.yml

```yml
version: '2'
services:
  php:
    build: container/wp
    container_name: cinra_wp
    volumes:
      - ./container/wp/html/assets:/var/www/html/assets
    ports:
      - 80:80
    links:
      - mysql
    environment:
      DOMAIN: cinra.dev
      APACHE_DOCROOT: /var/www/html
      APACHE_LOG_DIR: /var/log/apache2
      PROJECT_NAME: cinra
      WP_DB_HOST: mysql:3306
      WP_DB_NAME: cinra
      WP_DB_USER: cinra
      WP_DB_PASSWORD: 3030
  mysql:
    image: mysql:5.7.16
    container_name: cinra_mysql
    ports:
      - 3306:3306
    environment:
      MYSQL_ROOT_PASSWORD: 3030
      MYSQL_DATABASE: cinra
      MYSQL_USER: cinra
      MYSQL_PASSWORD: 3030
```

この設定ファイルで

- **Docker Machineに、cinra_php、cinra_mysqlというコンテナを一つずつ立ち上げる**
- **cinra_phpの方はポート80を公開、MySQLを使う**
- **cinra_mysqlは公式のmysql5.7.6のイメージを使って構築、3306のポートで接続できる**

という指定を行っている

- **version**: Docker Composeの書式バージョン。今んとこ無視してOK
- **services**: こいつが肝。コンテナの指定。`services/php`とすると、「Docker Machineの中に、`php`っつうコンテナを作るよ」という意味なんだけど、この`php`は、コンテナ名とは違うのでややこしい。
- **build**: Docker ImageをビルドするためのDockerfileの場所。この指定の代わりに`image`を使うことも出来る
- **image**: （mysqlの方参照）ビルドすっ飛ばして、既存のDocker Imageを利用するときは、`build`ではなく、こちらを指定する。基本的に、Docker HubからImageを引っ張ってくる形になる https://hub.docker.com
- **container_name**: コンテナ名。`docker ps -a`で見れる
- **volumes**: Volumeを指定する。Volumeについては後述
- **ports**: 公開するポートをポートフォワーディングで指定する
- **links**: `services`で設定しているコンテナの中から、リンクするコンテナを指定する
- **environment**: 環境変数。Dockerのお作法として、設定ファイルを環境毎に生成するのではなく、設定ファイル内から環境変数を利用するようなやり方をする。nginxとかは、設定ファイル内で環境変数を参照できないのでちょっと大変

### container/wp/Dockerfile

```
FROM cinra/php56

WORKDIR /var/www/html

COPY ./html /var/www/html
COPY ./template/wp-config-sample.php /var/www/html/wp/wp-config.php
```

この設定ファイルで

- **cinraのphp56というイメージをベースにDocker Imageを構築する**
- **ディレクトリ`/var/www/html`に移動して、仮想マシンに、`html`フォルダと、`wp-config-sample.php`ファイルをコピー**
- **`wp-config-sample.php`は`wp-config.php`にリネーム**

という指示を行っている。

- **FROM**: ベースにするDocker Image。docker-compose.ymlで指定したのと同様、基本、Docker Hubにあるものが使われる
- **WORKDIR**: 作業ディレクトリの指定
- **COPY**: ホストマシンから、仮想マシンにファイルを複製する機能。同様の機能に**ADD**があるが、こちらはzipファイルの解凍なども行う

### Volume

ボリュームとは、仮想マシン上で使えるホストマシンの領域の事を指す。常に同期している「共有フォルダ」のようなものと捉えてよし。