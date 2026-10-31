# Odonto Sonrisas
Plataforma web para una clínica odontológica (Laravel + PHP).
## Estado
- [x] CUS01 Registro, CUS02/03 Login, CUS04 Catálogo
- [ ] CUS05 Agendar cita, CUS06 Cancelar cita, CUS07 Historial
## Instalación
1. composer install
2. cp .env.example .env && php artisan key:generate
3. En .env: SESSION_DRIVER=file
4. php artisan serve
## Datos
Por ahora se guardan en JSON (storage/app/data). Se migrará a MySQL.