<?php

namespace App\Http\Services;

trait CrudService
{
    protected function renderActionModals($model, $editRoute, $deleteRoute)
    {
        $inputs = $this->modalInputs($model);
        $variables = $this->modalVariables();

        $buttons = view('dashboard.modals.actions', compact('model'))->render();
        $editModal = view('dashboard.modals.edit', compact('model', 'inputs', 'variables', 'editRoute'))->render();
        $deleteModal = view('dashboard.modals.delete', compact('model', 'deleteRoute'))->render();
        $showModal = view('dashboard.modals.show', compact('model', 'inputs', 'variables'))->render();

        return $buttons . $editModal . $deleteModal . $showModal;
    }



} //end of class
