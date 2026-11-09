<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, your projects will be transferred to other participants whenever possible.') }}
        </p>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        {{ __('Delete Account') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Your projects will be transferred before your account is deleted.
                This action cannot be undone.
            </p>

            {{-- Project ownership transfers --}}
            @foreach ($user->ownedProjects as $project)

                @php
                    $eligibleMembers = $project->members
                        ->where('id', '!=', $user->id);

                    $hasAdmin = $eligibleMembers->contains(
                        fn($member) => $member->pivot->role === 'admin'
                    );
                @endphp

                @if (!$hasAdmin)
                    <div class="mt-6">
                        <x-input-label for="transfer-{{ $project->id }}" value="New owner for: {{ $project->title }}" />

                        @if ($eligibleMembers->isEmpty())

                            <p class="mt-2 text-sm text-red-600">
                                This project has no eligible participants.
                                Add a participant or delete the project
                                before deleting your account.
                            </p>

                        @else

                            <select name="transfers[{{ $project->id }}]" id="transfer-{{ $project->id }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">
                                    Select a new owner
                                </option>

                                @foreach ($eligibleMembers as $member)
                                    <option value="{{ $member->id }}" @selected(
                                        old('transfers.' . $project->id) == $member->id
                                    )>
                                        {{ $member->name }}
                                        ({{ $member->pivot->contribution_hours }} hours)
                                    </option>
                                @endforeach
                            </select>

                        @endif

                        <x-input-error :messages="$errors->userDeletion->get('transfers.' . $project->id)" class="mt-2" />
                    </div>
                @endif

            @endforeach

            {{-- Password confirmation --}}
            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}" required />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            {{-- Modal buttons --}}
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>