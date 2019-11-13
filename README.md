# About this component

[![Status badge](https://img.shields.io/endpoint.svg?style=for-the-badge&url=https%3A//api-test.nl/api/v1/provider-latest-badge/5a146368-56a0-4092-8630-f806fc86ef50/)](https://api-test.nl/api/v1/provider-latest-badge/5a146368-56a0-4092-8630-f806fc86ef50/)
![Repo Status](https://img.shields.io/badge/status-concept-lightgrey.svg?style=plastic)

The request component handles request by a person to an organization, and validation thereof. Examples of requests could be a persons wishing to declare a relocation (verhuizing in dutch) to his new municipality or an intention to marry (melding voorgenomen huwelijk) to his or her municipality.   

Requests signify a request that has either been made by a person/organization to an specific organization, or an request that is being formulated by a person/organization but has not yet been submitted to an organization. As such an requests can be stateless and is by definition without consequence until it is submitted. They represent a form free option for anything that is not an case and a handling route for information that will never become an case.

Technically requests are designed to align with both [DSO]( https://redocly.github.io/redoc/?url=https://pre.omgevingswet.overheid.nl/knooppunt/apistore/api-docs/Rijkswaterstaat/Gebruikerstoepassingen-IndienenVerzoek/v1) and the case api [zaak-api]( https://zaken-api.vng.cloud/api/v1/schema), but primely serve to support a [process]( http://ptc.zaakonline.nl). As such, small design interpretation might differ.  They can be found in the [design considerations]( https://github.com/gemeenteshertogenbosch/verzoekregistratiecomponent/blob/master/DESIGN.md).
              
Request cannot be seen as an standalone entity as they take their definition from [request types]( http://vtc.zaakonline.nl/) where the actual rules that a request should adhere to are defined. Requests on them self only serve to store the in between and end results of processes.                

## Questions and contributing
Read more about how to ask questions, report or contribute (with code, documentaition or examples) in [`CONTRIBUTING.md`](CONTRIBUTING.md).

## Documentation

- [Development roadmap](ROADMAP.md)
- [How to contribute](CONTRIBUTING.md)
- [Installation of this component](INSTALLATION.md)
- [Making commonground components](TUTORIAL.md)
- [Securing this component](SECURITY.md)
- [Design considerations](DESIGN.md)

A hosted version of the OAS documentation and an demo version of the API can be found on http://vrc.zaakonline.nl
## Features
This repository uses the power of the [commonground proto component](https://github.com/ConductionNL/commonground-component) provide common ground specific functionality based on the [VNG Api Strategie](https://docs.geostandaarden.nl/api/API-Strategie/). Including  

* Build in support for public API's like BAG (Kadaster), KVK (Kamer van Koophandel)
* Build in validators for common dutch variables like BSN (Burger service nummer), RSIN(), KVK(), BTW()
* AVG and VNG proof audit trails, Wildcard searches, handling of incomplete date's and underInvestigation objects
* Support for NLX headers
* And [much more](https://github.com/ConductionNL/commonground-component) .... 

## Credits
This component was created by conduction (https://www.conduction.nl/team) for the municipality of ['s-Hertogenbosch](https://www.s-hertogenbosch.nl/). But based  on the [common ground proto component](https://github.com/ConductionNL/commonground-component). For more information on building your own common ground component please read the [tutorial](https://github.com/ConductionNL/commonground-component/blob/master/TUTORIAL.md).  

[!['s-Hertogenbosch](https://raw.githubusercontent.com/ConductionNL/verzoeken/master/resources/logo-s-hertogenbosch.svg?sanitize=true "'s-Hertogenbosch")](https://www.s-hertogenbosch.nl/)
[![Conduction](https://raw.githubusercontent.com/ConductionNL/verzoeken/master/resources/logo-conduction.svg?sanitize=true "Conduction")](https://www.conduction.nl/)

## License
Copyright � [Gemeente 's-Hertogenbosch](https://www.s-hertogenbosch.nl/) 2019

[Licensed under the EUPL](LICENCE.md)
