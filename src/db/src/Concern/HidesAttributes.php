<?php declare(strict_types=1);


namespace Swoft\Db\Concern;

/**
 * Class HidesAttributes
 *
 * @since 2.0
 */
trait HidesAttributes
{
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be visible in serialization.
     *
     * @var array
     */
    protected $visible = [];

    /**
     * Get the hidden attributes for the model.
     *
     * @return array
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set the hidden attributes for the model.
     *
     * @param array $hidden
     *
     * @return $this
     */
    public function setHidden(array $hidden)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Add hidden attributes for the model.
     *
     * @param array $attributes
     *
     * @return void
     */
    public function addHidden(array $attributes)
    {
        $this->visible = \array_diff($this->visible, $attributes);

        $this->hidden = \array_unique(\array_merge($this->hidden, $attributes));

    }

    /**
     * Get the visible attributes for the model.
     *
     * @return array
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set the visible attributes for the model.
     *
     * @param array $visible
     *
     * @return $this
     */
    public function setVisible(array $visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Add visible attributes for the model.
     *
     * @param array $attributes
     *
     * @return void
     */
    public function addVisible(array $attributes): void
    {
        $this->hidden  = \array_diff($this->hidden, $attributes);

        $this->visible = \array_unique(\array_merge($this->visible, $attributes));
    }

    /**
     * Make the given, typically hidden, attributes visible.
     *
     * @param array $attributes
     *
     * @return self
     */
    public function makeVisible(array $attributes): self
    {
        $this->addVisible($attributes);

        return $this;
    }

    /**
     * Make the given, typically visible, attributes hidden.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function makeHidden(array $attributes): self
    {
        $this->addHidden($attributes);

        return $this;
    }
}
