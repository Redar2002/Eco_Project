@include('errors.layout', ['code' => 403, 'title' => 'Accès refusé', 'message' => $exception->getMessage() ?: 'Vous n\'avez pas l\'autorisation d\'accéder à cette page.'])
