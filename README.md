ratchet-nette-session-provider
==============================

Nette session provider for Ratchet websocket server

usage:

$session = new Ratchet\Session\NetteSessionProvider(new App, $container->session);

$wsServer = new WsServer($session);

$server = IoServer::factory($wsServer, $port);
$server->run();