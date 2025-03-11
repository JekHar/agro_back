<?php

namespace App\Enums;

enum OrderLotStatus: string
{
    case PENDING = 'pending';
    case IN_PROCESS = 'in_process';
    case FINISHED = 'finished';
    case CANCELED = 'canceled';

    /**
     * Get the human-readable label for the status
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pendiente',
            self::IN_PROCESS => 'En Proceso',
            self::FINISHED => 'Finalizado',
            self::CANCELED => 'Cancelado',
        };
    }

    /**
     * Get the CSS class for the status
     */
    public function cssClass(): string
    {
        return match($this) {
            self::PENDING => 'bg-warning',
            self::IN_PROCESS => 'bg-primary',
            self::FINISHED => 'bg-success',
            self::CANCELED => 'bg-danger',
        };
    }

    /**
     * Get all available statuses as an array of [value => label]
     */
    public static function asSelectArray(): array
    {
        return [
            self::PENDING->value => self::PENDING->label(),
            self::IN_PROCESS->value => self::IN_PROCESS->label(),
            self::FINISHED->value => self::FINISHED->label(),
            self::CANCELED->value => self::CANCELED->label(),
        ];
    }
}