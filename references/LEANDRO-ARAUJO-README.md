# Hi Lio, I'm Leandro Araujo

### Organização do projeto
Foi criado uma Facade chamada Crawler que possui um 
método extract passando a pagina desejada, opcionalmente o 
valor padrão é 1.

Dentro da pasta `app > Extractors` ficam os clients
que podem ser conectados dentro do `.env`

```dotenv
# -------------------------------------- #
# ------- Extractor Configuration ------- #
# -------------------------------------- #
EXTRACTOR_CLIENT="americanas"
EXTRACTOR_URL="https://www.americanas.com.br/"
EXTRACTOR_TIMEOUT=15
```

### Endpoint para extração, exemplo cURL:
```php
<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://localhost/crawler/5',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
```
