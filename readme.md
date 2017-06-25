# Strawpoll API

Ceci est le backoffice de l'application du Strawpoll réalisée par Adam Attafi, Guillaume Peres et Sébastien Verneyre. Cette api est propulsée par Laravel dans sa version 5.4 et une base de données PostgreSQL.

# Documentation

## Routes

**GET** https://api.strawpoll.guillaumeperes.fr/api/duplicationchecks

Une requête en GET sur cette route retournera la liste des méthodes prises en charge par l'api pour contrôler le fait qu'un utilisateur puisse ou non voter plusieurs fois à un sondage.

Retourne un objet JSON de la forme :

```json
{
	"duplication_checks": [
		{
			"id": 1,
			"name": "method name",
			"label": "human readable method label"
		},
	]
}
```

**GET** https://api.strawpoll.guillaumeperes.fr/api/poll/{poll_id}/

Une requête en GET sur cette route retournera les données associées au sondage identifié par l'entier {poll_id}.

Retourne un objet JSON de la forme : 

```json
{
	"id": 1, 
	"has_captcha": true,
	"is_draft": true,
	"created": 1492303643,
	"updated": 1492303643,
	"published": 1492303643,
	"duplication_check": {
		"id": 1,
		"name": "method name",
		"label": "human readable method label"
	},
	"questions": [
		{
			"id": 1,
			"question": "Title of question 1",
			"multiple_answers": true,
			"answers": [
				{
					"id": 1,
					"answer": "Anwser 1",
				},
				{
					"id": 2,
					"answer": "Anwser 2",
				}
			]
		},
		{
			"id": 2,
			"question": "Title of question 2",
			"multiple_answers": true,
			"answers": [
				{
					"id": 3,
					"answer": "Anwser 3",
				},
				{
					"id": 4,
					"answer": "Anwser 4",
				}
			]
		}
	],
	"comments": [
		{
			"id": 1,
			"user": "Mike",
			"comment": "Comment text",
			"published": 1492303643
		},
		{
			"id": 2,
			"user": "Bob",
			"comment": "Another comment",
			"published": 1492303643
		}
	]
}
```

**POST** https://api.strawpoll.guillaumeperes.fr/api/poll/

Une requête en POST sur cette route permet de créer un sondage. Pour utiliser cette route, il faut envoyer les données du sondage dans le corps de la requête HTTP grâce à un formulaire ou sous la forme d'un objet JSON.

Les champs suivants sont pris en charge : 

| Nom du champ        | Type    | Obligatoire              | Description                                                                                  |
|---------------------|---------|--------------------------|----------------------------------------------------------------------------------------------|
| `duplication_check` | Integer | Oui                      | Identifiant d'une méthode contrôlant la possibilité pour un visiteur de voter plusieurs fois |
| `questions`         | Array   | Oui                      | Tableau de questions                                                                         |
| `user`              | Integer | Non (`null` par défaut)  | Identifiant de l'utilisateur ayant créé le sondage                                           |
| `has_captcha`       | Boolean | Non (`false` par défaut) | Ajout d'un captcha pour réduire le spam                                                      |
| `is_draft`          | Boolean | Non (`false` par défaut) | Statut du sondage                                                                            |

Exemple d'utilisation avec jQuery: 

```javascript
var data = {
	"duplication_check": 1,
	"questions": [
		{
			"question": "How are you?",
			"multiple_answers": false,
			"answers": ["Fine", "Good enough", "Hungry"]
		}
	],
	"user": 1,
	"has_captcha": true,
	"is_draft": false
};
$.post("https://api.strawpoll.guillaumeperes.fr/api/poll/", data, function(result) {
	console.log(result);
});
```

L'exemple ci-dessus va retourner une réponse sous la forme de l'objet JSON suivant : 

```json
{
	"code": 200,
	"message": "Le sondage a été enregistré",
	"data": {
		"poll_id": "pollid",
		"redirect": "https://api.strawpoll.guillaumeperes.fr/api/poll/{poll_id}/"
	}
}
```

En cas d'erreur, la réponse suivante sera retournée : 

```json
{
	"code": 500,
	"error": "Something wrong happened"
}
```

**POST** https://api.strawpoll.guillaumeperes.fr/api/poll/{poll_id}/answers/

Une requête en POST sur cette route permettra d'ajouter des votes au sondage identifié par {poll_id}.
Pour utiliser cette route, il faut lui envoyer un tableau contenant la liste des réponses pour lesquelles l'utilisateur souhaite voter (answers), ainsi qu'une chaine de caractères facultative contenant la valeur du `strawpoll_cookie` qui sera sauvegardée par l'api (cookie).

Les champs suivants sont pris en charge : 

| Nom du champ        | Type                   | Obligatoire | Description                 |
|---------------------|------------------------|-------------|-----------------------------|
| `answers`           | Array                  | Oui         | Tableau des réponses        |
| `cookie`            | Character varying(255) | Non         | Valeur du `strawpoll_cookie`|      

Exemple, retourne un objet JSON de la forme :

```json
{
    "answers": [1, 2],
    "cookie" : "j3fwp43o",
}
```

L'exemple ci-dessus va retourner une réponse sous la forme de l'objet JSON suivant :

```json
{
	"code": 200,
	"message": "Votre vote a été sauvegardé avec succès",
	"data": {
		"poll_id": "pollid",
		"redirect": "https://api.strawpoll.guillaumeperes.fr/api/poll/{poll_id}/results/"
	}
}
```

En cas d'erreur, la réponse suivante sera retournée :

```json
{
	"code": 500,
	"error": "Une erreur s'est produite"
}
```

**GET** https://api.strawpoll.guillaumeperes.fr/api/poll/{poll_id}/results/

Une requête en GET sur cette route retourne les résultats du sondage identifié par l'entier {poll_id}.

Retourne un objet JSON de la forme suivante : 

```json
{
	"code": 200,
	"message": "",
	"data": {
		"results": {
			"id": 1,
			"duplication_check": {
				"id": 1,
				"name": "notcontrolled",
				"label": "Autoriser plusieurs votes par utilisateur"
			},
			"total_votes": 15,
			"total_comments": 7,
			"questions": [
				{
					"id": 7,
					"question": "Comment allez-vous ?",
					"answers": [
						{
							"id": 1,
							"answer": "Très bien",
							"votes": 6,
							"color": "#FFFFFF" 
						},
						{
							"id": 2,
							"answer": "Moyennement bien",
							"votes": 5,
							"color": "#FEFEFE",
						},
						{
							"id": 3,
							"answer": "Pas très bien",
							"votes": 4,
							"color": "#000000"
						}
					]
				}
			]
		}
	}
}
```

**GET** https://api.strawpoll.guillaumeperes.fr/api/poll/{poll_id}/results/channel/

Une requête en GET sur cette route retourne le nom du channel socket.io sur lequel les clients doivent se connecter pour obtenir en temps réel les résultats du sondage identifié par l'entier {poll_id}.

Retourne un objet JSON de la forme : 

```json
{
	"code": 200,
	"message": "",
	"data": {
		"channel": "results-poll-1"
	}
}
```

## Résultats en temps réel

A venir.
