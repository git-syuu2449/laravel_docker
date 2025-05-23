<?php

namespace App\Enums;

enum HttpStatus: int
{
    // 情報
    case Continue = 100;
    case SwitchingProtocols = 101;

    // 成功
    case OK = 200;
    case Created = 201;
    case Accepted = 202;
    case NoContent = 204;

    // リダイレクト
    case MovedPermanently = 301;
    case Found = 302;
    case NotModified = 304;

    // クライアントエラー
    case BadRequest = 400;
    case Unauthorized = 401;
    case Forbidden = 403;
    case NotFound = 404;
    case MethodNotAllowed = 405;
    case UnprocessableEntity = 422;
    case TooManyRequests = 429;

    // サーバーエラー
    case InternalServerError = 500;
    case NotImplemented = 501;
    case BadGateway = 502;
    case ServiceUnavailable = 503;

    // ヘルパー
    public function message(): string
    {
        return match($this) {
            self::OK => 'OK',
            self::Created => 'Created',
            self::NoContent => 'No Content',
            self::BadRequest => 'Bad Request',
            self::Unauthorized => 'Unauthorized',
            self::Forbidden => 'Forbidden',
            self::NotFound => 'Not Found',
            self::UnprocessableEntity => 'Unprocessable Entity',
            self::InternalServerError => 'Internal Server Error',
            default => 'Unknown',
        };
    }
}
