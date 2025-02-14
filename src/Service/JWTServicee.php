<?php

namespace App\Service;

use DateTimeImmutable;
use InvalidArgumentException;

class JWTService
{

    // Ajout d'un constructeur pour accepter le secret
    private string $secret;
    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * Génère un JWT (JSON Web Token).
     *
     * @param array $header En-tête du JWT.
     * @param array $payload Données du JWT.
     * @param string $secret Clé secrète pour signer le JWT.
     * @param int $validity Durée de validité du token en secondes (par défaut 10800 secondes, soit 3 heures).
     * @return string Le JWT généré.
     */
    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string
    {
        if ($validity > 0) {
            $now = new DateTimeImmutable();
            $payload['iat'] = $now->getTimestamp(); // Timestamp d'émission
            $payload['exp'] = $now->getTimestamp() + $validity; // Timestamp d'expiration
        }

        // Encodage en base64 de l'en-tête et du payload
        $base64Header = $this->base64UrlEncode(json_encode($header));
        $base64Payload = $this->base64UrlEncode(json_encode($payload));

        // Génération de la signature
        $signature = $this->generateSignature($base64Header, $base64Payload, $secret);
        $base64Signature = $this->base64UrlEncode($signature);

        // Construction du JWT
        return $base64Header . '.' . $base64Payload . '.' . $base64Signature;
    }

    /**
     * Vérifie si le token est correctement formé.
     *
     * @param string $token Le JWT à vérifier.
     * @return bool True si le token est valide, sinon false.
     */
    public function isValid(string $token): bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_]+\.[a-zA-Z0-9\-\_]+\.[a-zA-Z0-9\-\_]+$/',
            $token
        ) === 1;
    }

    /**
     * Récupère le payload du JWT.
     *
     * @param string $token Le JWT.
     * @return array Le payload décodé.
     * @throws InvalidArgumentException Si le token est invalide.
     */
    public function getPayload(string $token): array
    {
        if (!$this->isValid($token)) {
            throw new InvalidArgumentException('Token invalide.');
        }

        $parts = explode('.', $token);
        return json_decode($this->base64UrlDecode($parts[1]), true);
    }

    /**
     * Récupère l'en-tête du JWT.
     *
     * @param string $token Le JWT.
     * @return array L'en-tête décodé.
     * @throws InvalidArgumentException Si le token est invalide.
     */
    public function getHeader(string $token): array
    {
        if (!$this->isValid($token)) {
            throw new InvalidArgumentException('Token invalide.');
        }

        $parts = explode('.', $token);
        return json_decode($this->base64UrlDecode($parts[0]), true);
    }

    /**
     * Vérifie si le JWT a expiré.
     *
     * @param string $token Le JWT.
     * @return bool True si le token a expiré, sinon false.
     * @throws InvalidArgumentException Si le token est invalide.
     */
    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);

        if (!isset($payload['exp'])) {
            throw new InvalidArgumentException('Le token ne contient pas de date d\'expiration.');
        }

        $now = new DateTimeImmutable();
        return $payload['exp'] < $now->getTimestamp();
    }

    /**
     * Vérifie la signature du JWT.
     *
     * @param string $token Le JWT.
     * @param string $secret La clé secrète utilisée pour signer le JWT.
     * @return bool True si la signature est valide, sinon false.
     * @throws InvalidArgumentException Si le token est invalide.
     */
    public function check(string $token, string $secret): bool
    {
        if (!$this->isValid($token)) {
            throw new InvalidArgumentException('Token invalide.');
        }

        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        // Régénère un token avec les mêmes en-tête et payload pour vérifier la signature
        $verifToken = $this->generate($header, $payload, $secret, 0);

        return hash_equals($token, $verifToken);
    }

    /**
     * Encode une chaîne en base64 URL-safe.
     *
     * @param string $data La chaîne à encoder.
     * @return string La chaîne encodée.
     */
    private function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * Décode une chaîne en base64 URL-safe.
     *
     * @param string $data La chaîne à décoder.
     * @return string La chaîne décodée.
     */
    private function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }

    /**
     * Génère la signature HMAC-SHA256.
     *
     * @param string $base64Header L'en-tête encodé en base64.
     * @param string $base64Payload Le payload encodé en base64.
     * @param string $secret La clé secrète.
     * @return string La signature générée.
     */
    private function generateSignature(string $base64Header, string $base64Payload, string $secret): string
    {
        return hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);
    }
}