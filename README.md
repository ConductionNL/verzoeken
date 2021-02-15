# Requests Registration Component / VRC

Description
----
De verzoeken catalogus bevat zowel het eind als tussenresultaat van een proces. Of om het iets simpeler te zeggen een proces schrijft bij iedere stap zijn variabelen weg naar een verzoek. Daarmee is het proces stateless en het verzoek stateful. In deze zin is een verzoek vergelijkbaar met een proces token in bijvoorbeeld BPMN- engines.  

Hierdoor is het mogelijk om verzoeken uit te wisselen tussen processen. Het is bijvoorbeeld mogelijk om via een chatbot een proces te draaien dat het verzoektype verhuizen gebruikt om een verzoek aan te maken, maar het ondertekenen van dit verzoek te laten doen via het webformulier.

Vanuit deze gedachte bevat een verzoek dus alle variabelen die horen bij de klantreis gekoppeld aan een verzoektype. Waarbij uiteindelijk het verzoek type doorslaggevend is voor de validiteit en de processen alleen de manier van aanleveren bepalen.

Dit betekent ook dat verzoeken een nog niet afgeronde klantreis kan zijn, ofwel gebruikers kunnen hun klantreis parkeren en later vervolgen. Het betekent ook dat deze klantreizen in principe inzichtelijk zijn voor de aanbieder (gemeente), met andere woorden als een gebruiker in een formulier vast loopt, kan hij/zij de helpdesk bellen en kan een helpdeskmedewerker meekijken en aanvullen. Sterker nog, doordat wijzigingen aan verzoeken op gebruikersniveau worden gelogd en het indienen een losse actie is, zou het ook denkbaar zijn dat een medewerker of externe partij een verzoek klaarzet voor een gebruiker om te controleren en te ondertekenen. 

Dit maakt het mogelijk om bijvoorbeeld een verhuizer een verhuismelding te laten klaar zetten voor de gebruiker die de gebruiker overneemt, ondertekent en opstuurt.  Of bijvoorbeeld een aannemer die een bouwvergunning voor een dakkapel klaarzet.

Overigens hoeft een verzoek niet zijn grondslag te vinden in een proces (bijvoorbeeld vanuit de Processen Catalogus). De enige randvoorwaarde van een verzoek is dat deze op het moment van indienen voldoet aan de voorwaarde van het bijbehorende verzoektype.

Het is derhalve goed denkbaar dat simpele verzoeken worden ingeschoten en ondertekend door IOT devices zo als een lantaarnpaal die zelf meldt dat hij kapot is.

Additional Information
----

- [Contributing](CONTRIBUTING.md)
- [ChangeLogs](CHANGELOG.md)
- [RoadMap](ROADMAP.md)
- [Security](SECURITY.md)
- [Licence](LICENSE.md)


Installation
----
We differentiate between two way's of installing this component, a local installation as part of the provided developers toolkit or an [helm](https://helm.sh/) installation on an development or production environment. 

#### Local installation
First make sure you have [docker desktop](https://www.docker.com/products/docker-desktop) running on your computer. Then clone the repository to a directory on your local machine through a [git command](https://github.com/git-guides/git-clone) or [git kraken](https://www.gitkraken.com) (ui for git). If successful you can now navigate to the directory of your cloned repository in a command prompt and execute docker-compose up. 
```CLI
$ docker-compose up
```
This will build the docker image and run the used containers and when seeing the log from the php container: "NOTICE: ready to handle connections", u are ready to view the documentation at localhost on your preferred browser.

#### Instalation on Kubernetes or Haven
As a haven compliant commonground component this component is installable on kubernetes trough helm. The helm files can be found in the api/helm folder. For installing this component trough helm simply open your (still) favorite command line interface and run 
```CLI
$ helm install [name] ./api/helm --kubeconfig kubeconfig.yaml --namespace [name] --set settings.env=prod,settings.debug=0,settings.cache=1
```
For an in depth installation guide you can refer to the [installation guide](INSTALLATION.md), it also contains a short tutorial on getting your cluster ready to expose your installation to the world

Standards
----

This component adheres to international, national and local standards (in that order), notable standards are:

- Any applicable [W3C](https://www.w3.org) standard, including but not limited to [rest](https://www.w3.org/2001/sw/wiki/REST), [JSON-LD](https://www.w3.org/TR/json-ld11/) and [WEBSUB](https://www.w3.org/TR/websub/)
- Any applicable [schema](https://schema.org/) standard
- [OpenAPI Specification](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.0.md)
- [GAIA-X](https://www.data-infrastructure.eu/GAIAX/Navigation/EN/Home/home.html)
- [Publiccode](https://docs.italia.it/italia/developers-italia/publiccodeyml-en/en/master/index.html), see the [publiccode](api/public/schema/publiccode.yaml) for further information
- [Forum Stanaardisatie](https://www.forumstandaardisatie.nl/open-standaarden)
- [NL API Strategie](https://docs.geostandaarden.nl/api/API-Strategie/)
- [Common Ground Realisatieprincipes](https://componentencatalogus.commonground.nl/20190130_-_Common_Ground_-_Realisatieprincipes.pdf)
- [Haven](https://haven.commonground.nl/docs/de-standaard)
- [NLX](https://docs.nlx.io/understanding-the-basics/introduction)
- [Standard for Public Code](https://standard.publiccode.net/), see the [compliancy scan](publiccode.md) for further information. 

Developers toolkit and technical information
----
You can find the data model, OAS documentation and other helpfull developers material like a  postman collection under api/public/schema, development support is provided trough the [samenorganiseren slack channel](https://join.slack.com/t/samenorganiseren/shared_invite/zt-dex1d7sk-wy11sKYWCF0qQYjJHSMW5Q).

Couple of quick tips when you start developing
- If you not yet have setup the component locally read the Tutorial for setting up your local environment.
- You can find the other components on [Github](https://github.com/ConductionNL).
- Take a look at the [commonground componenten catalogus](https://componentencatalogus.commonground.nl/componenten?) to prevent development collitions. 
- Use [Commongroun.conduction.nl](https://commonground.conduction.nl/) for easy deployment of test environments to deploy your development to.
- For information on how to work with the component you can refer to the tutorial [here](TUTORIAL.md).
  

Contributing
----
First of al please read the [Contributing](CONTRIBUTING.md) guideline's ;)

But most imporantly, welcome! We strife to keep an active community at [commonground.nl](https://commonground.nl/), please drop by and tell is what you are thinking about so that we can help you along.


Credits
----

Information about the authors of this component can be found [here](AUTHORS.md)





Copyright © [Gemeente 's-Hertogenbosch](https://www.s-hertogenbosch.nl/) 2019

[Licensed under the EUPL](LICENSE.md)
