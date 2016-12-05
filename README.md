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

**docker-machine start (default)**: Docker Machine（≒ VMインスタンス）を立ち上げる

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
