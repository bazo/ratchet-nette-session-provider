<?php
namespace Bazo\Ratchet;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsServerInterface;
/**
 * NetteSessionProvider
 *
 * @author Martin Bažík
 */

/**
 * This component will allow access to session data from your Nette Framework website for each user connected
 */
class NetteSessionProvider implements MessageComponentInterface, WsServerInterface
{
	protected 
		/**
		* @var Ratchet\MessageComponentInterface
		*/
		$app,

		/** 
		 * @var \Nette\Http\Session 
		 */
		$session
	;

	/**
	 * @param MessageComponentInterface $app
	 * @param \Nette\Http\Session $session 
	 */
	public function __construct(MessageComponentInterface $app, \Nette\Http\Session $session)
	{
		$this->app = $app;
		$this->session = $session;
	}

	/**
	 * {@inheritdoc}
	 */
	function onOpen(ConnectionInterface $conn)
	{
		session_id($conn->WebSocket->request->getCookie($this->session->getName()));
		$conn->session = $this->session;
		return $this->app->onOpen($conn);
	}

	/**
	 * {@inheritdoc}
	 */
	function onMessage(ConnectionInterface $from, $msg)
	{
		return $this->app->onMessage($from, $msg);
	}

	/**
	 * {@inheritdoc}
	 */
	function onClose(ConnectionInterface $conn)
	{
		// "close" session for Connection

		return $this->app->onClose($conn);
	}

	/**
	 * {@inheritdoc}
	 */
	function onError(ConnectionInterface $conn, \Exception $e)
	{
		return $this->app->onError($conn, $e);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSubProtocols()
	{
		if ($this->app instanceof WsServerInterface)
		{
			return $this->app->getSubProtocols();
		}
		else
		{
			return array();
		}
	}

	/**
	 * @param string Input to convert
	 * @return string
	 */
	protected function toClassCase($langDef)
	{
		return str_replace(' ', '', ucwords(str_replace('_', ' ', $langDef)));
	}

}