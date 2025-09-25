<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

function idn_message()
{
    return [
        'accepted'             => ':attribute harus diterima.',
        'active_url'           => ':attribute bukan URL yang valid.',
        'after'                => ':attribute harus tanggal setelah :date.',
        'after_or_equal'       => ':attribute harus tanggal setelah atau sama dengan :date.',
        'alpha'                => ':attribute hanya boleh berisi huruf.',
        'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
        'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
        'array'                => ':attribute harus berupa array.',
        'before'               => ':attribute harus tanggal sebelum :date.',
        'before_or_equal'      => ':attribute harus tanggal sebelum atau sama dengan :date.',
        'between'              => [
            'numeric' => ':attribute harus antara :min dan :max.',
            'file'    => ':attribute harus antara :min dan :max kilobyte.',
            'string'  => ':attribute harus antara :min dan :max karakter.',
            'array'   => ':attribute harus memiliki antara :min dan :max item.',
        ],
        'boolean'              => ':attribute harus bernilai true atau false.',
        'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
        'date'                 => ':attribute bukan tanggal yang valid.',
        'date_equals'          => ':attribute harus sama dengan :date.',
        'date_format'          => ':attribute tidak cocok dengan format :format.',
        'different'            => ':attribute dan :other harus berbeda.',
        'digits'               => ':attribute harus terdiri dari :digits digit.',
        'digits_between'       => ':attribute harus terdiri antara :min dan :max digit.',
        'dimensions'           => ':attribute memiliki dimensi gambar yang tidak valid.',
        'distinct'             => ':attribute memiliki nilai duplikat.',
        'email'                => ':attribute harus berupa alamat email yang valid.',
        'ends_with'            => ':attribute harus diakhiri dengan salah satu dari berikut: :values.',
        'exists'               => ':attribute yang dipilih tidak valid.',
        'file'                 => ':attribute harus berupa file.',
        'filled'               => ':attribute harus memiliki nilai.',
        'gt'                   => [
            'numeric' => ':attribute harus lebih besar dari :value.',
            'file'    => ':attribute harus lebih besar dari :value kilobyte.',
            'string'  => ':attribute harus lebih panjang dari :value karakter.',
            'array'   => ':attribute harus memiliki lebih dari :value item.',
        ],
        'gte'                  => [
            'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
            'file'    => ':attribute harus lebih besar dari atau sama dengan :value kilobyte.',
            'string'  => ':attribute harus lebih panjang dari atau sama dengan :value karakter.',
            'array'   => ':attribute harus memiliki :value item atau lebih.',
        ],
        'image'                => ':attribute harus berupa gambar.',
        'in'                   => ':attribute yang dipilih tidak valid.',
        'in_array'             => ':attribute tidak ada dalam :other.',
        'integer'              => ':attribute harus berupa bilangan bulat.',
        'ip'                   => ':attribute harus berupa alamat IP yang valid.',
        'ipv4'                 => ':attribute harus berupa alamat IPv4 yang valid.',
        'ipv6'                 => ':attribute harus berupa alamat IPv6 yang valid.',
        'json'                 => ':attribute harus berupa JSON string yang valid.',
        'lt'                   => [
            'numeric' => ':attribute harus kurang dari :value.',
            'file'    => ':attribute harus kurang dari :value kilobyte.',
            'string'  => ':attribute harus lebih pendek dari :value karakter.',
            'array'   => ':attribute harus memiliki kurang dari :value item.',
        ],
        'lte'                  => [
            'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
            'file'    => ':attribute harus kurang dari atau sama dengan :value kilobyte.',
            'string'  => ':attribute harus lebih pendek dari atau sama dengan :value karakter.',
            'array'   => ':attribute harus tidak lebih dari :value item.',
        ],
        'max'                  => [
            'numeric' => ':attribute tidak boleh lebih besar dari :max.',
            'file'    => ':attribute tidak boleh lebih besar dari :max kilobyte.',
            'string'  => ':attribute tidak boleh lebih panjang dari :max karakter.',
            'array'   => ':attribute tidak boleh memiliki lebih dari :max item.',
        ],
        'mimes'                => ':attribute harus berupa file dengan tipe: :values.',
        'mimetypes'            => ':attribute harus berupa file dengan tipe: :values.',
        'min'                  => [
            'numeric' => ':attribute harus minimal :min.',
            'file'    => ':attribute harus minimal :min kilobyte.',
            'string'  => ':attribute harus minimal :min karakter.',
            'array'   => ':attribute harus memiliki minimal :min item.',
        ],
        'not_in'               => ':attribute yang dipilih tidak valid.',
        'not_regex'            => 'Format :attribute tidak valid.',
        'numeric'              => ':attribute harus berupa angka.',
        'password'             => 'Kata sandi salah.',
        'present'              => ':attribute harus ada.',
        'regex'                => 'Format :attribute tidak valid.',
        'required'             => 'Kolom :attribute harus diisi.',
        'required_if'          => 'Kolom :attribute harus diisi ketika :other adalah :value.',
        'required_unless'      => 'Kolom :attribute harus diisi kecuali :other memiliki nilai :values.',
        'required_with'        => 'Kolom :attribute harus diisi ketika terdapat :values.',
        'required_with_all'    => 'Kolom :attribute harus diisi ketika terdapat :values.',
        'required_without'     => 'Kolom :attribute harus diisi ketika tidak terdapat :values.',
        'required_without_all' => 'Kolom :attribute harus diisi ketika tidak terdapat nilai :values.',
        'same'                 => ':attribute dan :other harus sama.',
        'size'                 => [
            'numeric' => ':attribute harus berukuran :size.',
            'file'    => ':attribute harus berukuran :size kilobyte.',
            'string'  => ':attribute harus berukuran :size karakter.',
            'array'   => ':attribute harus mengandung :size item.',
        ],
        'starts_with'          => ':attribute harus diawali dengan salah satu dari berikut: :values.',
        'string'               => ':attribute harus berupa string.',
        'timezone'             => ':attribute harus berupa zona waktu yang valid.',
        'unique'               => ':attribute sudah ada sebelumnya.',
        'uploaded'             => ':attribute gagal diunggah.',
        'url'                  => 'Format :attribute tidak valid.',
        'uuid'                 => ':attribute harus berupa UUID yang valid.',
        // custom mwy
        'no_html_or_script'    => ':attribute tidak boleh mengandung tag HTML atau skrip JavaScript.',
    ];

}

function validate_and_response(array $field)
{
    try {
        $validator = [];
        $attribute = [];
        foreach ($field as $key => $column) {
            $validator[$key] = $column[1];
            $attribute[$key] = $column[0];
        }
        $validate = Validator::make(request()->all(), $validator, idn_message(), $attribute)->validate();
    } catch (\Throwable $e) {
        throw $e;
    }
}