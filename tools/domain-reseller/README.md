# Domain Reseller

Separate SmartToolz tool for a domain-reseller workflow.

## Structure

- `index.php` — frontend domain search UI
- `api.php` — server-side API endpoint

## Next integration

Connect `api.php` to the chosen domain registrar/reseller API using server-side credentials. Add payment processing before enabling the Register action.

Never put registrar API keys in browser JavaScript or commit real secrets to GitHub.
