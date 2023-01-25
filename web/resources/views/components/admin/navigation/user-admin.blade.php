@props([
	'uid'
])

<x-admin.navigation-tabs>
	<x-admin.navigation-tab-link label="Account" :route="route('admin.useradmin', ['ID' => $uid])" />
	<x-admin.navigation-tab-link label="Moderate" :route="route('admin.manualmoderateuser', ['ID' => $uid])" />
	<x-admin.navigation-tab-link label="Transactions" :route="route('admin.dashboard')" />
	<x-admin.navigation-tab-link label="Trades" :route="route('admin.dashboard')" />
	<x-admin.navigation-tab-link label="Send Personal Msg" :route="route('admin.dashboard')" />
	<x-admin.navigation-tab-link label="Messages" :route="route('admin.dashboard')" />
	<x-admin.navigation-tab-link label="Adjust Assets" :route="route('admin.dashboard')" />
	@owner
		<x-admin.navigation-tab-link label="IP" :route="route('admin.dashboard')" />
		<x-admin.navigation-tab-link label="MAC Address" :route="route('admin.dashboard')" />
	@endowner
</x-admin.navigation-tabs>