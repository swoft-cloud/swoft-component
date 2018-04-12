# Component
This reposiory is used to manage all swoft components.  
All components will NOT be modified in the original repository of component, SHOULD ALWAYS be modified in this repository, also commit and push to this repository, and use `git subtree push` to sync changes to the original repository of component.

# Usage
## Add an Repository as an Sub Repository
`git subtree add --prefix=src/$Folder [repository] [ref] --squash`  
e.g. Add [swoft/pipeline](https://github.com/swoft-cloud/swoft-pipeline) component as an Sub Repository, `git subtree add --prefix=src/pipeline git@github.com:swoft-cloud/swoft-pipeline master --squash`

## Commit changes
Just use `git commit` as usual, and Push to this repository

## Sync changes to the Original Repository of Component
`git subtree push --prefix=src/$Folder [repository] [ref]`  
e.g. Add [swoft/pipeline](https://github.com/swoft-cloud/swoft-pipeline) component as an Sub Repository, `git subtree push --prefix=src/pipeline git@github.com:swoft-cloud/swoft-pipeline master`

> Tips:
> You could use `remote` to instead of `[repository]` property for easier to use.  
> e.g. Add `Remote` first, `git remote add -f pipeline git@github.com:swoft-cloud/swoft-pipeline.git`,  
> after this, you can use `pipeline` as `[repository]`,  
> for example `git subtree push --prefix=src/pipeline pipeline master`
