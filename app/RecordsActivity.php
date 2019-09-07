<?php

namespace App;


trait RecordsActivity
{
	public $oldAttributes = [];

	/**
	 * Boot the trait on the eloquent model
	 */
	public static function bootRecordsActivity()
	{
		foreach( self::recordableEvents() as $event) {
			static::$event(function($model) use ($event) {
				$model->recordActivity($model->activityDescription($event));
			});

			if($event === 'updated') {
				static::updating( function ( $model ) {
					$model->oldAttributes = $model->getOriginal();
				} );
			}
		}
	}

	/**
	 * Return the description of the activity on the model
	 *
	 * @param $description
	 *
	 * @return string
	 */
	protected function activityDescription($description)
	{
		return "{$description}_" . strtolower( class_basename( $this ) );
	}

	/**
	 * Check for any recordable events overridden
	 *
	 * @return array
	 */
	public static function recordableEvents() {
		if ( isset( static::$recordableEvents ) ) {
			return static::$recordableEvents;
		}
		return [ 'created', 'updated' ];
	}

	/**
	 * Save the activity to the activity model and related models.
	 *
	 * @param $description
	 */
	public function recordActivity( $description ) {
		$this->activity()->create( [
			'user_id' => ($this->project ?? $this)->owner->id,
			'description' => $description,
			'changes'     => $this->activityChanges(),
			'project_id'  => class_basename( $this ) === 'Project' ? $this->id : $this->project_id
		] );
	}

	/**
	 * Provide the morph relationship
	 *
	 * @return mixed
	 */
	public function activity()
	{
		return $this->morphMany(Activity::class, 'subject')->latest();
	}

	/**
	 * Check if any activity existed on the model
	 *
	 * @return array
	 */
	protected function activityChanges()
	{
		if($this->wasChanged()) {
			return [
				'before' => array_except(array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'),
				'after' => array_except($this->getChanges(), 'updated_at')
			];
		}
	}
}