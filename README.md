# Residence-Example

## Descripción

Este proyecto es una aplicación Laravel orientada a la gestión de muestras de maíz, agricultores y zonas geográficas (estados, municipios, localidades). Utiliza Filament para la administración y presenta una estructura modular y escalable.

## Estructura de Carpetas

- **app/Models/**
	- `State.php`, `Municipality.php`, `Locality.php`, `Farmer.php`, `MaizeSample.php`, `MaizeSubSample.php`, `User.php`
- **app/Filament/Resources/**
	- Recursos Filament para CRUD y administración de cada entidad principal.
- **database/migrations/**
	- Migraciones para usuarios, estados, municipios, localidades, agricultores, muestras y sub-muestras de maíz.
- **app/Console/Commands/**
 	- Comando para importar catálogo INEGI: `ImportInegiCatalog.php`

## Funcionalidades Principales

- **Administración de Zonas:** CRUD de estados, municipios y localidades, con relaciones entre ellos.
- **Agricultores:** CRUD de agricultores, con campos de nombre, teléfono y correo.
- **Muestras de Maíz:** Registro de muestras, asociadas a recolector, agricultor, municipio y localidad. Cada muestra puede tener varias sub-muestras con mediciones y foto.
- **Importación de Catálogo INEGI:** Comando para importar estados, municipios y localidades desde la API oficial.
- **Panel Administrativo:** Filament provee interfaces para gestionar todos los recursos anteriores.

## Migraciones

Las migraciones definen las siguientes tablas principales:

- `states`
- `municipalities`
- `localities`
- `farmers`
- `maize_samples`
- `maize_sub_samples`
- `users`

## Rutas Actuales

- `/`  
  Muestra la vista principal [`welcome.blade.php`](resources/views/welcome.blade.php).

- `/administrador`  
  Panel administrativo Filament, acceso a los recursos de gestión.

## Filament Resources

- [`StateResource`](app/Filament/Resources/StateResource.php)  
  Permite la administración de estados. Incluye campos como clave y nombre, y gestiona la relación con municipios.

- [`MunicipalityResource`](app/Filament/Resources/MunicipalityResource.php)  
  Permite la administración de municipios. Relaciona cada municipio con un estado y gestiona sus localidades.

- [`LocalityResource`](app/Filament/Resources/LocalityResource.php)  
  Permite la administración de localidades. Relaciona cada localidad con un municipio.

- [`FarmerResource`](app/Filament/Resources/FarmerResource.php)  
  Permite la administración de agricultores, incluyendo nombre, teléfono y correo.

- [`UserResource`](app/Filament/Resources/UserResource.php)  
  Permite la administración de usuarios del sistema, con campos de nombre, correo y contraseña.

- [`MaizeSampleResource`](app/Filament/Resources/MaizeSampleResource.php)  
  Permite registrar y administrar muestras de maíz, asociadas a recolector, agricultor, municipio y localidad. Permite agregar sub-muestras con mediciones y fotos.

## Descripción General de los Resources

- **StateResource:** CRUD de estados, clave y nombre.
- **MunicipalityResource:** CRUD de municipios, vinculación con estado.
- **LocalityResource:** CRUD de localidades, vinculación con municipio.
- **FarmerResource:** CRUD de agricultores, datos personales.
- **UserResource:** CRUD de usuarios del sistema.
- **MaizeSampleResource:** Registro de muestras y sub-muestras de maíz, métricas y fotos.

---

## Comando de Importación

El comando `ImportInegiCatalog` permite poblar la base de datos con información oficial de zonas geográficas.

## Frontend

La vista principal es `welcome.blade.php`, con estilos personalizados y soporte para Tailwind.

---

> _Actualizado al 29 de septiembre de 2025_
