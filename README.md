# Backend Developer Test - Cuponeria

This test aims to test the candidate's knowledge regarding the technologies used by the **Cuponeria Backend Developer Team**.

## Instructions

Clone this repository.
Create a new branch with your name.
Checkout to the branch of your name.
Commit your workflow, you can check this [article](https://medium.com/@rafael.oliveira/como-escrever-boas-mensagens-de-commit-9f8fe852155a).
After you're done, push to the origin and send a pull request of the branch with your name.


## To run the container

Download and install [docker](https://www.docker.com/products/docker-desktop)
Initialize the container with the following command
```
docker-compose up
```
access
<http://localhost/>

## Skills Required

- You have to use [Lúmen](https://lumen.laravel.com/docs/8.x/)	as your framework.
- Create a endpoint that receive the following request <http://localhost/crawler/(page)>.
- The page is a integer param that specifies the page to be crawled
![pages](https://i.imgur.com/wX0BpJw.jpeg "pages")

- Create a crawler service that get a product list (name | price) from the following base url <https://www.submarino.com.br/busca/tv> for page 1
- Page 2 URL is <https://www.submarino.com.br/busca/tv?limite=24&offset=24>
- Keep return following pages if exists (3,4,5,...)
- Use MVC model
- Return a JSON containning all the products from the main feed with the following format from the specific page
```json
[{
		"name": "Smart Tv Lg 55 55un7310 4k Uhd Wifi Bluetooth Hdr Inteligência Artificial Thinq Ai Google Assistente",
		"price": 2879.99
	},
	{
		"name": "Smart TV LG 43'' 43UN7300 Ultra HD 4K WiFi Bluetooth HDR Inteligência Artificial ThinQ AI Google Assistente Alexa IOT",
		"price": 2049.99
	}
]
```

## GLHF (Good Luck and Have Fun!)
