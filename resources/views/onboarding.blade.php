<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Merchant Onboarding</title>
</head>
<body>
    <form method="POST" action="/onboarding">
        @csrf
        <div>
            <label>Team Name
                <input type="text" name="team_name" value="{{ old('team_name') }}">
            </label>
            @error('team_name')<div>{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Store Name
                <input type="text" name="store_name" value="{{ old('store_name') }}">
            </label>
            @error('store_name')<div>{{ $message }}</div>@enderror
        </div>
        <div>
            <label>
                <input type="checkbox" name="gdpr_consent" value="1"> I agree to the EU GDPR terms
            </label>
            @error('gdpr_consent')<div>{{ $message }}</div>@enderror
        </div>
        <button type="submit">Get Started</button>
    </form>
</body>
</html>
