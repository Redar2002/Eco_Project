<x-account-layout title="Mes commandes" subtitle="Retrouvez l'historique et le suivi de vos commandes.">
    @include('account.orders._list', ['orders' => $orders])
    <div class="mt-8">{{ $orders->links() }}</div>
</x-account-layout>
