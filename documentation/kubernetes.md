# Helm
This project has a helm chart, in order to use it, the Docker image must first be built and loaded into a repo.

After this is done, copy the file from `helm/values.yml` and fill it in. Here you can see a small example with fields that have to be filled in:

````yml
deployment:
    image: <url to image>
    files:
        # Location of .env file
        env: <path to .env file>
        # Location of storage folder
        storage: <path to storage folder>

ingress:
    url: <url to reach shaark instance>
    #optional
    annotations:
        cert-manager.io/issuer: "<issuer name when used cert-manager>"
````
if no cert-manager is used, the appropriate certificate must be stored under <name>-secret-tls as secret

Once this has been done, you can install the helm chart as follows: `helm install <name> ./helm --values <edit-values-file>`

The installation comes without mysql and redis if this is required, this must be installed externally by existing charts from the intenet and then the corresponding kubernetes-url must be stored in the .env as host
