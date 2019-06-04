<?php

namespace Omnipay\Best2Pay\Message;

use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Class AbstractRequest
 * @package Omnipay\Best2Pay\Message
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
		/**
		 * Method name from bank API
		 *
		 * @return string
		 */
		abstract public function getAction(): string;

		/**
		 * Create Signature.
		 *
		 * @param $signatureString
		 * @return string
		 */
		public function buildSignature(string $signatureString): string
		{
				$hash = md5($signatureString);
				return base64_encode($hash);
		}

		/**
		 * Get endpoint URL
		 *
		 * @return string
		 */
		public function getEndPoint(): string
		{
				return $this->getParameter('endPoint');
		}

		/**
		 * Set endpoint URL
		 *
		 * @param string $endPoint
		 * @return $this
		 */
		public function setEndPoint(string $endPoint): self
		{
				return $this->setParameter('endPoint', $endPoint);
		}

		/**
		 * @return string
		 */
		public function getPassword(): string
		{
				return $this->getParameter('password');
		}

		/**
		 * @param string $password
		 * @return AbstractRequest
		 */
		public function setPassword(string $password): self
		{
				return $this->setParameter('password', $password);
		}

		/**
		 * @return string
		 */
		public function getLanguage(): string
		{
				return $this->getParameter('lang');
		}

		/**
		 * @param $value
		 * @return $this
		 */
		public function setLanguage(string $value): self
		{
				return $this->setParameter('lang', $value);
		}

		/**
		 * @return int
		 */
		public function getSector(): int
		{
				return $this->getParameter('sector');
		}

		/**
		 * @param int $value
		 * @return AbstractRequest
		 */
		public function setSector(int $value): self
		{
				return $this->setParameter('sector', $value);
		}

		/**
		 * @return string
		 */
		public function getSignature(): string
		{
				return $this->getParameter('signature');
		}

		/**
		 * @param $value
		 * @return AbstractRequest
		 */
		public function setSignature(string $value): self
		{
				return $this->setParameter('signature', $value);
		}

		/**
		 * @return string
		 */
		public function getHttpMethod(): string
		{
				return 'POST';
		}

		/**
		 * Get Request headers
		 *
		 * @return array
		 */
		public function getHeaders(): array
		{
				return [
						"content-type" => 'application/x-www-form-urlencoded'
				];
		}

		/**
		 * @inheritdoc
		 *
		 * @param mixed $data
		 * @return object|\Omnipay\Common\Message\ResponseInterface
		 * @throws \ReflectionException
		 */
		public function sendData($data): ResponseInterface
		{
				$url = $this->getEndPoint() . $this->getAction();
				$httpResponse = $this->httpClient->request(
						$this->getHttpMethod(),
						$url,
						$this->getHeaders(),
						http_build_query($data, '', '&')
				);

				$responseClassName = str_replace('Request', 'Response', \get_class($this));
				$reflection = new \ReflectionClass($responseClassName);
				if (!$reflection->isInstantiable()) {
						throw new RuntimeException(
								'Class ' . str_replace('Request', 'Response', \get_class($this)) . ' not found'
						);
				}

				return $reflection->newInstance($this, $httpResponse->getBody()->getContents());
		}

		/**
		 * Add additional params to data
		 *
		 * @param array $data
		 * @param array $additionalParams
		 * @return array
		 */
		public function specifyAdditionalParameters(array $data, array $additionalParams): array
		{
				foreach ($additionalParams as $param) {
						$method = 'get' . ucfirst($param);
						if (method_exists($this, $method)) {
								$value = $this->{$method}();
								if ($value) {
										$data[$param] = $value;
								}
						}
				}
				return $data;
		}

		/**
		 * Validate Request parameters
		 */
		public function validate(): void
		{
				foreach (func_get_args() as $key) {
						$value = $this->parameters->get($key);
						if (!isset($value) || empty($value)) {
								throw new \DomainException("The $key parameter is required");
						}
				}
		}
}
