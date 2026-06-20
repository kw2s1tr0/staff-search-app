# APIトークン認証

CLIやAIエージェントからAPIを利用する場合は、Laravel Sanctumが発行するBearerトークンを使用します。Web画面は従来どおりセッション認証を使用します。

## トークンの発行

既存ユーザーのメールアドレスとパスワード、利用元を識別する名前を送信します。

```bash
curl -X POST http://localhost/api/auth/login \
  -H 'Accept: application/json' \
  -H 'Content-Type: application/json' \
  -d '{
    "email": "user@example.com",
    "password": "password",
    "token_name": "local-agent-cli"
  }'
```

成功時は、`access_token`、`token_type`、`expires_at`が返ります。平文のトークンが返るのは発行時だけです。有効期限は発行から30日で、期限切れ後は再度ログインして発行します。

## APIの利用

トークンを環境変数など、Git管理外の安全な保存先へ設定します。

```bash
export SSAPP_API_TOKEN='1|発行されたトークン'

curl http://localhost/api/auth/me \
  -H 'Accept: application/json' \
  -H "Authorization: Bearer ${SSAPP_API_TOKEN}"

curl http://localhost/api/employees \
  -H 'Accept: application/json' \
  -H "Authorization: Bearer ${SSAPP_API_TOKEN}"
```

トークンをコマンド履歴、ログ、ソースコード、`.env`以外のGit管理対象ファイルへ記録しないでください。本番環境では必ずHTTPSを使用します。

## ログアウト

ログアウトすると、リクエストに使用したトークンだけが直ちに失効します。同じユーザーが別の`token_name`で発行したトークンには影響しません。

```bash
curl -X POST http://localhost/api/auth/logout \
  -H 'Accept: application/json' \
  -H "Authorization: Bearer ${SSAPP_API_TOKEN}"
```
