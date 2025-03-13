<?php
namespace Mlab\Webhook\Traits;

trait Serializable {

    public function __toArray(): array {
        return get_object_vars($this);
    }

    public function __toJson(): string {
        return json_encode($this->__toArray());
    }

    public function __fromArray(array $data): void {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function __fromJson(string $data): void {
        $this->__fromArray(json_decode($data, true));
    }

    public function __toString(): string {
        return $this->__toJson();
    }
}