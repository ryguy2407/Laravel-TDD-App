<div class="card mt-3" style="display: flex;flex-direction: column;">
    <h3 class="font-normal text-xl mb-6 py-4 -ml-5 border-l-4 border-blue pl-4">
        Invite a user
    </h3>

    <form action="{{ $project->path() }} . /invitations" method="POST" class="text-right">
        @csrf

        <div class="mb-3">
            <input type="text" name="email" placeholder="Email Address" class="border border-grey rounded w-full p-2">
        </div>
        <button type="submit" class="button">Invite</button>
    </form>

    @include('errors', ['bag' => 'invitations'])
</div>