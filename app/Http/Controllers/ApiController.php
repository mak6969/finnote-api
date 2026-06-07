<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "FinNote API Documentation",
    version: "1.0.0",
    description: "API untuk Aplikasi Catatan Keuangan FinNote - UAS Pemrograman API",
    contact: new OA\Contact(email: "your.email@example.com")
)]
#[OA\Server(
    url: "http://127.0.0.1:8000/api",
    description: "Local Server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
class ApiController extends Controller
{
    // Kosongkan saja, ini hanya untuk dokumentasi
}