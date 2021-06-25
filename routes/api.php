<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    phpinfo();
    return $router->app->version();
});
$router->get('/verificar', 'LinkController@copy');
// settings
$router->get('/v1/settings', 'SettingController@index');
// visitas
$router->get('/v1/visitas', 'VisitaController@index');
// user
$router->post('/v1/auth', 'AuthController@authenticate');
$router->post('/v1/users/whoami', 'UserController@whoami');
$router->get('/v1/users', 'UserController@index');
$router->get('/v1/users/{id}', 'UserController@show');
$router->post('/v1/users', 'UserController@store');
$router->put('/v1/users/{id}/attributes/{attribute}', 'UserController@updateAttributes');
$router->put('/v1/users/{id}', 'UserController@update');
$router->delete('/v1/users/{id}', 'UserController@destroy');
// team
$router->get('/v1/team/group/{type}', 'TeamController@index');
$router->get('/v1/team/alcalde', 'TeamController@alcalde');
// upload
$router->post('/v1/uploads/images', 'UploadController@uploadImage');
// links
$router->post('/v1/links', 'LinkController@store');
$router->get('/v1/links/{id}', 'LinkController@show');
$router->get('/v1/links', 'LinkController@index');
$router->put('/v1/links/{id}', 'LinkController@update');
$router->delete('/v1/links/{id}', 'LinkController@destroy');
// posts
$router->get('/v1/posts', 'PostController@index');
$router->get('/v1/posts/{id}', 'PostController@show');
$router->get('/v1/posts/details/title', 'PostController@showByTitle');
// postcategoria
$router->get('/v1/postcategorias', 'PostCategoriaController@index');
// carouseles
$router->get('/v1/carouseles', 'CarouselController@index');
// banners
$router->get('/v1/banners/{id}', 'BannerController@show');
// comiciones
$router->get('/v1/comisiones/{year}/{month}', 'ComisionController@show');
// gerencias
$router->get('/v1/gerencias', 'GerenciaController@index');
// proyectos
$router->get('/v1/proyectos', 'ProyectoController@index');
// cas
$router->get('/v1/cas', 'CasController@index');
// normativas y informaciones
$router->get('/v1/normativas', 'NormativaController@index');
$router->get('/v1/informaciones', 'NormativaController@getInformaciones');
// transporte y licencia
$router->get('/v1/empresas', 'TransporteController@index');
$router->get('/v1/empresas/{empresa_id}/transportes', 'TransporteController@padronPorEmpresa');
// Libro de reclamaciones
$router->post('/v1/libroreclamaciones/create', 'LibroReclamacionController@create');
// gastronomias
$router->get('/v1/gastronomias', 'GastronomiaController@index');
$router->get('/v1/turismos', 'GastronomiaController@getTurismos');
$router->get('/v1/agroindustrias', 'GastronomiaController@getAgroindustrias');
$router->get('/v1/habitaciones', 'GastronomiaController@getHabitaciones');
$router->get('/v1/restaurantes', 'GastronomiaController@getRestaurantes');
// topbars
$router->get('/v1/topbars', 'TopbarController@index');
$router->get('/v1/topbars/actives', 'TopbarController@active');
$router->put('/v1/topbars/{id}', 'TopbarController@index');
// modalbienvenida
$router->get('/v1/notificationmodal/actives', 'ModalNotificationController@actives');