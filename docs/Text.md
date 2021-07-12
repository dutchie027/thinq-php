# Text Messaging

[Thinq Text Messaging](https://apidocs.thinq.com/#bac2ace6-7777-47d8-931e-495b62f01799)

## Sending an SMS

$api = new dutchie027\Thinq\API(THINQ_USER, THINQ_TOKEN, $settings);

$api->text->sendText('1234567890', '5559871212', 'test from the API');
