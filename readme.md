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

## Routes en développement

**POST** https://api.strawpoll.guillaumeperes.fr/api/poll/{poll_id}/answers/

Permettra d'ajouter des votes au sondage identifié par {poll_id}.

**GET** https://api.strawpoll.guillaumeperes.fr/api/poll/{poll_id}/answers/

Permettra de récupérer les votes du sondage identifié par {poll_id}.
