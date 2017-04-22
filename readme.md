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

**POST** http://strawpoll.guillaumeperes.fr/api/poll/

Une requête en POST sur cette route permet de créer un sondage. Pour utiliser cette route, il faut envoyer les données du sondage dans le corps de la requête HTTP grâce à un formulaire ou sous la forme d'un objet JSON.

Les champs suivants sont pris en charge : 

`duplication_check` : entier représentant l'identifiant d'une méthode contrôlant la possibilité pour un visiteur de voter plusieurs fois (**Obligatoire**)

`user` : entier représentant l'identifiant de l'utilisateur conencté qui a créé le sondage (**Optionnel** `null` par défaut)

`has_captcha` : booléen déterminant si le sondage fera usage d'un captcha pour empêcher le spam (**Optionnel** `false` par défaut)

`multiple_answers` : booléen déterminant si un visiteur peut sélectionner plusieurs réponses parmi celles qui lui sont proposées (**Optionnel** `false` par défaut)

`is_draft`: booléen déterminant si le sondage est enregistré en tant que brouillon, ne sera pris en compte que si le sondage a été créé par un utilisateur connecté (**Optionnel** `false` par défaut et si le sondage est créé par un utilisateur non-conencté)

`question` : texte de la question (**Obligatoire**)

`answers` : tableau de réponses textuelles (**Obligatoire** et comportant un minimum de deux réponses)

Exemple d'utilisation avec jQuery: 

```javascript
var data = {
	"duplication_check": 1,
	"user": 1,
	"has_captcha": true,
	"multiple_answers": true,
	"is_draft": false,
	"question": "How are you?",
	"answers": ["Answer1", "Answer2", "Answer3"],
};
$.post("http://strawpoll.guillaumeperes.fr/api/poll/", data, function(result) {
	console.log(result);
});
```

L'exemple ci-dessus va retourner une réponse sous la forme de l'objet JSON suivant : 

```json
{
	"code": 200,
	"message": "Le sondage a été enregistré",
	"data": {
		"redirect": "http://route/vers/le/sondage/"
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

**POST** http://strawpoll.guillaumeperes.fr/api/poll/{poll_id}/answers/

Permettra d'ajouter des votes au sondage identifié par {poll_id}.

**GET** http://strawpoll.guillaumeperes.fr/api/poll/{poll_id}/answers/

Permettra de récupérer les votes du sondage identifié par {poll_id}.
