<?php

namespace App\Repositories;

class JsonRepositorio
{
    private string $ruta;

    public function __construct(string $archivo)
    {
        $this->ruta = storage_path('app/data/' . $archivo);
        if (!is_dir(dirname($this->ruta))) {
            mkdir(dirname($this->ruta), 0755, true);
        }
        if (!file_exists($this->ruta)) {
            file_put_contents($this->ruta, json_encode([]));
        }
    }
    public function todos(): array
    {
        return json_decode(file_get_contents($this->ruta), true) ?? [];
    }
    public function buscarPor(string $campo, $valor): ?array
    {
        foreach ($this->todos() as $registro) {
            if (($registro[$campo] ?? null) == $valor) {
                return $registro;
            }
        }
        return null;
    }
    public function crear(array $registro): array
    {
        $datos = $this->todos();
        $registro['id'] = count($datos) > 0 ? max(array_column($datos, 'id')) + 1 : 1;
        $datos[] = $registro;
        file_put_contents($this->ruta, json_encode($datos, JSON_PRETTY_PRINT));
        return $registro;
    }
}