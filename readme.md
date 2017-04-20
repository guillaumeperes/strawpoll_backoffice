# Strawpoll API

Ceci est le backoffice de l'application du Strawpoll réalisée par Adam Attafi, Guillaume Peres et Sébastien Verneyre. Cette api est propulsée par Laravel dans sa version 5.4 et une base de données PostgreSQL.

# Documentation

## Routes

**GET** http://strawpoll.guillaumeperes.fr/api/duplicationchecks

Une requête en GET sur cette route retournera la liste des méthodes prises en charge par l'api pour contrôler le fait qu'un utilisateur puisse ou non voter plusieurs fois à un sondage. Retourne un objet JSON sous la forme : 

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

**GET** http://strawpoll.guillaumeperes.fr/api/poll/{poll_id}/

Une requête en GET sur cette route retournera les données associées au sondage identifié par l'entier {poll_id}.

Retourne un objet JSON de la forme : 

```json
{
	"id": 1, 
	"has_captcha": true,
	"multiple_answers": true,
	"is_draft": true,
	"created": 1492303643,
	"updated": 1492303643,
	"published": 1492303643,
	"duplication_check": {
		"id": 1,
		"name": "method name",
		"label": "human readable method label"
	},
	"question": {
		"id": 1, 
		"question": "Question title",
		"answers": [
			{
				"id": 1,
				"answer": "Anwser 1",
				"position": 0
			},
			{
				"id": 2,
				"answer": "Anwser 2",
				"position": 1
			}
		]
	},
	"comments": [
		{
			"id": 1,
			"user": "Mike",
			"comment": "Comment text",
			"published": 1492303643
		}
	]
}
```

## Routes en développement

**POST** http://strawpoll.guillaumeperes.fr/api/poll/

Permettra de créer un sondage.

**POST** http://strawpoll.guillaumeperes.fr/api/poll/{poll_id}/answers/

Permettra d'ajouter des votes au sondage identifié par {poll_id}.

**GET** http://strawpoll.guillaumeperes.fr/api/poll/{poll_id}/answers/

Permettra de récupérer les votes du sondage identifié par {poll_id}.
