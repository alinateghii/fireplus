<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleConfig extends Model
{
	use HasFactory;

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var string[]|bool
	 */
	protected $guarded = [];

	protected $primaryKey = ['module_id', 'name'];

	public $incrementing = false;

	public function module()
	{
		return $this->belongsTo(Module::class);
	}

	/**
	 * Set the keys for a save update query.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	protected function setKeysForSaveQuery($query)
	{
		$keys = $this->getKeyName();
		if (!is_array($keys)) {
			return parent::setKeysForSaveQuery($query);
		}

		foreach ($keys as $keyName) {
			$query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
		}

		return $query;
	}

	/**
	 * Get the primary key value for a save query.
	 *
	 * @param mixed $keyName
	 * @return mixed
	 */
	protected function getKeyForSaveQuery($keyName = null)
	{
		if (is_null($keyName)) {
			$keyName = $this->getKeyName();
		}

		if (isset($this->original[$keyName])) {
			return $this->original[$keyName];
		}

		return $this->getAttribute($keyName);
	}

	public function castType($type, $value)
	{
		switch ($type) {
			case 'boolean':
				return $value ? true : false;
			case 'integer':
				return (int) $value;
			default:
				return $value;
		}
	}

	public function jsonSerialize()
	{
		$json = parent::jsonSerialize();
		$json['value'] = $this->castType($this->type, $this->value);
		return $json;
	}
}