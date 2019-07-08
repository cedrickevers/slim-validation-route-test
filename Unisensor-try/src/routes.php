<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

use Respect\Validation\Validator as V;

return function (App $app) {
    $container = $app->getContainer();
    // Container : Contient un ensemble de possibilité d'option d'utilisation de slim (objet, require,tableaux...=


    //methode avec 2 arguments  la route et  le callback, dans le call back il ya 3 argument, dont un optionnel
    //requete : la var $requête est un objet comprenant la rêquete de l'utilisateur.
    //$response: // le résultat de la requête
     //$arg Contient tous les placeholder (tableau associatif)

    // []Furthermore parts of the route enclosed in [...] are considered optional,
    // so that /foo[bar] will match both /foo and /foobar. Optional parts are only supported in a trailing position, not in the middle of a route.
    // {} placeholder : permet un traitement sur différente requête ex index/content , index/contact
   $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Define name
       $name = $args['name'] ? $args['name'] : 'index';

        // Sample log message
       // ajoute une ligne 'entrée  dans le log
        $container->get('logger')->info("$name '/$name' route");

        // Render index view
        //return $container->get('renderer')->render($response, 'index.phtml', $args);

       //Est ce que l'on renvoi une page prévue ? si oui on l'affiche correctement...
       try {
            return $container->get('renderer')->render($response, "$name.phtml", $args);
            //... sinon on attrape l'erreur et on redirige l'utilisateur sur home.
       } catch(RuntimeException $e) {
           return $response->withRedirect('/');
       }
    });

    $app->post('/contact', function (Request $request, Response $response) use ($container) {
        // Sample log message
        $container->get('logger')->info("contact '/contact' route post");

        // The validate method returns the validator instance
        //Utilisation du service de validation de slim(install awurth)
        // voir https://github.com/awurth/SlimValidation
        $validator = $container->validator->validate($request, [

            // voir=> https://respect-validation.readthedocs.io/en/1.1/rules/NotBlank/
            'nom' => V::notBlank(),
            'prenom' => V::notBlank(),
            'email' => V::email(),
            'phone' => V::phone(),
            'message' => V::notBlank(),
            // ...
        ]);

        // Recupere les valeurs POST de la requete
        // JSON / XML / URL encoder

        //Récupère les valeurs recues (POSt,GET...)  sous forme de tableau associatif
        $post = $request->getParsedBody();

        // On valide les entrées (POST)

        if ($validator->isValid()) {  // => https://github.com/awurth/SlimValidation => usage

            // Do something...

            // Render contact view to say succes
            return $container->get('renderer')->render($response, 'contact.phtml', array("notshowform" => true));
        } else {
            $errors = $validator->getErrors();

            // Render contact view with errors

            // Permet d'afficher les valeurs du tableau associatif.
            return $container->get('renderer')->render($response, 'contact.phtml', array_merge($post, $errors));
            /*=>
              post = array(
	nom => monnom,
	prenom => monprenom,
	mail => "",
)

errors = array(
	mail => array(
		email => msg
	)
}

merge = array(
	nom => monnom,
	prenom => monprenom,
	mail => array(
		email => msg
	)
)
            */
        }
    });
};
