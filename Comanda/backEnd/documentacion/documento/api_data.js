define({ "api": [
  {
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "optional": false,
            "field": "varname1",
            "description": "<p>No type.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "varname2",
            "description": "<p>With type.</p>"
          }
        ]
      }
    },
    "type": "",
    "url": "",
    "version": "0.0.0",
    "filename": "./documentacion/documento/main.js",
    "group": "C__xampp_htdocs_TP_PROG3_1C_2018_Comanda_backEnd_documentacion_documento_main_js",
    "groupTitle": "C__xampp_htdocs_TP_PROG3_1C_2018_Comanda_backEnd_documentacion_documento_main_js",
    "name": ""
  },
  {
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "optional": false,
            "field": "varname1",
            "description": "<p>No type.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "varname2",
            "description": "<p>With type.</p>"
          }
        ]
      }
    },
    "type": "",
    "url": "",
    "version": "0.0.0",
    "filename": "./documentacion/template/main.js",
    "group": "C__xampp_htdocs_TP_PROG3_1C_2018_Comanda_backEnd_documentacion_template_main_js",
    "groupTitle": "C__xampp_htdocs_TP_PROG3_1C_2018_Comanda_backEnd_documentacion_template_main_js",
    "name": ""
  },
  {
    "type": "any",
    "url": "/HabilitarCORS4200/",
    "title": "HabilitarCORS4200",
    "version": "0.1.0",
    "name": "HabilitarCORS4200",
    "group": "MIDDLEWARE",
    "description": "<p>Por medio de este MiddleWare habilito que se pueda acceder desde http://localhost:4200</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "ServerRequestInterface",
            "optional": false,
            "field": "request",
            "description": "<p>El objeto REQUEST.</p>"
          },
          {
            "group": "Parameter",
            "type": "ResponseInterface",
            "optional": false,
            "field": "response",
            "description": "<p>El objeto RESPONSE.</p>"
          },
          {
            "group": "Parameter",
            "type": "Callable",
            "optional": false,
            "field": "next",
            "description": "<p>The next middleware callable.</p>"
          }
        ]
      }
    },
    "examples": [
      {
        "title": "Como usarlo:",
        "content": "->add(\\verificador::class . ':HabilitarCORS4200')",
        "type": "json"
      }
    ],
    "filename": "./MW/MWparaCORS.php",
    "groupTitle": "MIDDLEWARE"
  },
  {
    "type": "any",
    "url": "/HabilitarCORS8080/",
    "title": "HabilitarCORS8080",
    "version": "0.1.0",
    "name": "HabilitarCORS8080",
    "group": "MIDDLEWARE",
    "description": "<p>Por medio de este MiddleWare habilito que se pueda acceder desde http://localhost:8080</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "ServerRequestInterface",
            "optional": false,
            "field": "request",
            "description": "<p>El objeto REQUEST.</p>"
          },
          {
            "group": "Parameter",
            "type": "ResponseInterface",
            "optional": false,
            "field": "response",
            "description": "<p>El objeto RESPONSE.</p>"
          },
          {
            "group": "Parameter",
            "type": "Callable",
            "optional": false,
            "field": "next",
            "description": "<p>The next middleware callable.</p>"
          }
        ]
      }
    },
    "examples": [
      {
        "title": "Como usarlo:",
        "content": "->add(\\verificador::class . ':HabilitarCORS8080')",
        "type": "json"
      }
    ],
    "filename": "./MW/MWparaCORS.php",
    "groupTitle": "MIDDLEWARE"
  },
  {
    "type": "any",
    "url": "/HabilitarCORSTodos/",
    "title": "HabilitarCORSTodos",
    "version": "0.1.0",
    "name": "HabilitarCORSTodos",
    "group": "MIDDLEWARE",
    "description": "<p>ASDADASDSADSADADA</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "ServerRequestInterface",
            "optional": false,
            "field": "request",
            "description": "<p>El objeto REQUEST.</p>"
          },
          {
            "group": "Parameter",
            "type": "ResponseInterface",
            "optional": false,
            "field": "response",
            "description": "<p>El objeto RESPONSE.</p>"
          },
          {
            "group": "Parameter",
            "type": "Callable",
            "optional": false,
            "field": "next",
            "description": "<p>The next middleware callable.</p>"
          }
        ]
      }
    },
    "examples": [
      {
        "title": "Como usarlo:",
        "content": "->add(\\verificador::class . ':HabilitarCORSTodos')",
        "type": "json"
      }
    ],
    "filename": "./MW/MWparaCORS.php",
    "groupTitle": "MIDDLEWARE"
  }
] });
