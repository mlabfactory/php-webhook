# php-webhook
A simple webhook server to manage API service requests, currently in version 0-alpha.

## Install
vendor/bin/phinx migrate DB migrations

## Usage
This is an API webhook configurator. You can invoke a POST request to `/create` to create a new endpoint webhook. The API will respond with a hash code that you can use to submit new webhook events.

Example:
1. `curl -X POST http://localhost/create --data-raw '{"domain" : "http://myserver.com"}' --header 'X-API-TOKEN: yourTokenSetInEnvVariable'`
2. The create method will respond with a hash code and queue UUID, which you can use for subsequent requests.
3. `curl -X POST /http[/{queueId}/{followUpUri}] --data-raw '{"payload" : "test"}' --header 'X-HASH: hashCode'`

## Webhook Types
Following is the list of webhooks you can invoke:
- http