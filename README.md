# 概要

#### Bitbucketからの自動デプロイ

BitbucketのWebHooksを使用して、自動的にリモートサーバに更新資産を反映させるスクリプトです。

#### 動作環境

リモートサーバのPHP環境にて動作します。
レンタルサーバ（さくらインターネットなど）に配置し実行することも可能です。

# 利用方法

#### 1. リモートサーバでローカルリポジトリを作成

    $ git clone https://xxxx@bitbucket.org/xxxx/xxxx.git ./

#### 2. BitbucketでWebhooksを設定

    ・https://xxxx/webhook-bitbucket.php
    ・Triggers：Repository push

#### 3. スクリプトを配置し、ホスト名をを定義

    ・webhook-bitbucket.php をリモートサーバに配置（公開フォルダ）
    ・BitBucketで設定したＵＲＬのホスト名を定義
    ※WEBHOOK_HOST　例) xxxxxxx.sakura.ne.jp

#### 4. BitbucketにPUSHすると自動的にサーバに反映される

