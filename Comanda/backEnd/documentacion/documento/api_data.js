define({ "api": [
  {
    "type": "post",
    "url": "/login/",
    "title": "login",
    "version": "0.1.0",
    "name": "login",
    "group": "API",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "email",
            "description": "<p>correo electronico del usuario</p>"
          },
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "password",
            "description": "<p>contraseña del usuario</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "email:",
          "content": "email: \"chriseze21@gmail.com\"",
          "type": "json"
        },
        {
          "title": "password: ",
          "content": "password: \"a12345\"",
          "type": "json"
        }
      ]
    },
    "filename": "./index.php",
    "groupTitle": "API"
  },
  {
    "type": "get",
    "url": "/logout/",
    "title": "logout",
    "version": "0.1.0",
    "name": "logout",
    "group": "API",
    "filename": "./index.php",
    "groupTitle": "API"
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
    "type": "delete",
    "url": "/",
    "title": "",
    "version": "0.1.0",
    "name": "DeleteTable",
    "description": "<p>elimina una mesa</p>",
    "group": "Table",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "id",
            "description": "<p>id de la mesa a borrar</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "id:",
          "content": "id: \"1\"",
          "type": "json"
        }
      ]
    },
    "filename": "./index.php",
    "groupTitle": "Table"
  },
  {
    "type": "get",
    "url": "/tables/",
    "title": "tables",
    "version": "0.1.0",
    "name": "GetTables",
    "description": "<p>obtiene las mesas por estado</p>",
    "group": "Table",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "status",
            "description": "<p>0: cerrada 1: con clientes esperando 2: con clientes comiendo 3: con clientes pagando</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "status:",
          "content": "status: \"1\"",
          "type": "json"
        }
      ]
    },
    "filename": "./index.php",
    "groupTitle": "Table"
  },
  {
    "type": "post",
    "url": "/",
    "title": "",
    "version": "0.1.0",
    "name": "SaveNewTable",
    "description": "<p>guarda una nueva mesa</p>",
    "group": "Table",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "code",
            "description": "<p>codigo de 5 caracteres</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "code:",
          "content": "code: \"00001\"",
          "type": "json"
        }
      ]
    },
    "filename": "./index.php",
    "groupTitle": "Table"
  },
  {
    "type": "delete",
    "url": "/",
    "title": "",
    "version": "0.1.0",
    "name": "DeleteUser",
    "description": "<p>elimina o recupera al usuario con el id enviado por parametro</p>",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "id",
            "description": "<p>id del usuario</p>"
          },
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "status",
            "description": "<p>0: elimina 1: habilita</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "id:",
          "content": "id: \"1\"",
          "type": "json"
        },
        {
          "title": "status:",
          "content": "status: \"0\"",
          "type": "json"
        }
      ]
    },
    "filename": "./index.php",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "/",
    "title": "",
    "version": "0.1.0",
    "name": "GetAllUsers",
    "description": "<p>obtiene todos los usuarios</p>",
    "group": "User",
    "filename": "./index.php",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "/logs/",
    "title": "logs",
    "version": "0.1.0",
    "name": "GetLogs",
    "description": "<p>obtiene los ingresos de un usuario</p>",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "id",
            "description": "<p>id del usuario</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "id:",
          "content": "id: \"1\"",
          "type": "json"
        }
      ]
    },
    "filename": "./index.php",
    "groupTitle": "User"
  },
  {
    "type": "get",
    "url": "/user/",
    "title": "user",
    "version": "0.1.0",
    "name": "GetUser",
    "description": "<p>obtiene un usuario por id</p>",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "id",
            "description": "<p>id del usuario</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "id:",
          "content": "id: \"1\"",
          "type": "json"
        }
      ]
    },
    "filename": "./index.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "/",
    "title": "",
    "version": "0.1.0",
    "name": "SaveUser",
    "description": "<p>edita un usuario si se envia el parametro id, sino agrega uno nuevo</p>",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "text",
            "optional": true,
            "field": "id",
            "description": "<p>id del usuario a editar</p>"
          },
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "name",
            "description": "<p>nombre del usuario</p>"
          },
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "email",
            "description": "<p>correo electronico del usuario</p>"
          },
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "password",
            "description": "<p>contraseña del usuario</p>"
          },
          {
            "group": "Parameter",
            "type": "text",
            "optional": true,
            "field": "sectorId",
            "description": "<p>0: BarraTragosVinos 1: BarraChoperasCervezaArtesanal 2: Cocina 3: CandyBar</p>"
          },
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "role",
            "description": "<p>1: Administrador 2: moso 3: operativo</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "id:",
          "content": "id: \"1\"",
          "type": "json"
        },
        {
          "title": "name:",
          "content": "name: \"Cristian\"",
          "type": "json"
        },
        {
          "title": "email:",
          "content": "email: \"chriseze21@gmail.com\"",
          "type": "json"
        },
        {
          "title": "password: ",
          "content": "password: \"a12345\"",
          "type": "json"
        },
        {
          "title": "sectorId:",
          "content": "sectorId: \"1\"",
          "type": "json"
        },
        {
          "title": "role:",
          "content": "role: \"1\"",
          "type": "json"
        }
      ]
    },
    "filename": "./index.php",
    "groupTitle": "User"
  },
  {
    "type": "put",
    "url": "/",
    "title": "",
    "version": "0.1.0",
    "name": "SuspendUser",
    "description": "<p>suspende o habilita  al usuario con el id enviado por parametro</p>",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "id",
            "description": "<p>id del usuario</p>"
          },
          {
            "group": "Parameter",
            "type": "text",
            "optional": false,
            "field": "status",
            "description": "<p>0: suspende 1: habilita</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "id:",
          "content": "id: \"1\"",
          "type": "json"
        },
        {
          "title": "status:",
          "content": "status: \"0\"",
          "type": "json"
        }
      ]
    },
    "filename": "./index.php",
    "groupTitle": "User"
  }
] });
