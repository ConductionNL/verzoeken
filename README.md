Verzoeken Registratie Component / VRC
-----

De verzoeken catalogus bevat zowel het eind als tussenresultaat van een proces. Of om het iets simpeler te zeggen een proces schrijft bij iedere stap zijn variabelen weg naar een verzoek. Daarmee is het proces stateless en het verzoek stateful. In deze zin is een verzoek vergelijkbaar met een proces token in bijvoorbeeld BPMN- engines.  

Hierdoor is het mogelijk om verzoeken uit te wisselen tussen processen. Het is bijvoorbeeld mogelijk om via een chatbot een proces te draaien dat het verzoektype verhuizen gebruikt om een verzoek aan te maken, maar het ondertekenen van dit verzoek te laten doen via het webformulier.

Vanuit deze gedachte bevat een verzoek dus alle variabelen die horen bij de klantreis gekoppeld aan een verzoektype. Waarbij uiteindelijk het verzoek type doorslaggevend is voor de validiteit en de processen alleen de manier van aanleveren bepalen.

Dit betekent ook dat verzoeken een nog niet afgeronde klantreis kan zijn, ofwel gebruikers kunnen hun klantreis parkeren en later vervolgen. Het betekent ook dat deze klantreizen in principe inzichtelijk zijn voor de aanbieder (gemeente), met andere woorden als een gebruiker in een formulier vast loopt, kan hij/zij de helpdesk bellen en kan een helpdeskmedewerker meekijken en aanvullen. Sterker nog, doordat wijzigingen aan verzoeken op gebruikersniveau worden gelogd en het indienen een losse actie is, zou het ook denkbaar zijn dat een medewerker of externe partij een verzoek klaarzet voor een gebruiker om te controleren en te ondertekenen. 

Dit maakt het mogelijk om bijvoorbeeld een verhuizer een verhuismelding te laten klaar zetten voor de gebruiker die de gebruiker overneemt, ondertekent en opstuurt.  Of bijvoorbeeld een aannemer die een bouwvergunning voor een dakkapel klaarzet.

Overigens hoeft een verzoek niet zijn grondslag te vinden in een proces (bijvoorbeeld vanuit de Processen Catalogus). De enige randvoorwaarde van een verzoek is dat deze op het moment van indienen voldoet aan de voorwaarde van het bijbehorende verzoektype.

Het is derhalve goed denkbaar dat simpele verzoeken worden ingeschoten en ondertekend door IOT devices zo als een lantaarnpaal die zelf meldt dat hij kapot is.

## Credits
This component was created by conduction (https://www.conduction.nl/team) for the municipality of ['s-Hertogenbosch](https://www.s-hertogenbosch.nl/). But based  on the [common ground proto component](https://github.com/ConductionNL/commonground-component). For more information on building your own common ground component please read the [tutorial](https://github.com/ConductionNL/commonground-component/blob/master/TUTORIAL.md).  

[!['s-Hertogenbosch](https://raw.githubusercontent.com/ConductionNL/processes/master/resources/logo-s-hertogenbosch.svg?sanitize=true "'s-Hertogenbosch")](https://www.s-hertogenbosch.nl/)
[![Conduction](https://raw.githubusercontent.com/ConductionNL/processes/master/resources/logo-conduction.svg?sanitize=true "Conduction")](https://www.conduction.nl/)

## License
Copyright � [Gemeente 's-Hertogenbosch](https://www.s-hertogenbosch.nl/) 2019

[Licensed under the EUPL](LICENCE.md)
