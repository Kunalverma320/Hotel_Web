<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Staff extends User
{
    protected $table = 'users';

    public function newEloquentBuilder($query)
    {
        return new class($query) extends Builder {
            public function where($column, $operator = null, $value = null, $boolean = 'and')
            {
                if ($column === 'department') {
                    $val = func_num_args() === 2 ? $operator : $value;
                    return $this->whereHas('employee.department', function ($q) use ($val) {
                        $q->where('name', 'like', '%' . $val . '%');
                    });
                }
                return parent::where($column, $operator, $value, $boolean);
            }
        };
    }
}
