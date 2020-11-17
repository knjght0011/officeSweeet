### Let's get started...

1. Switch to master `master` 

    `git checkout master`
    
1. Firstly, pull to make sure your copy is up-to-date

    `git pull`
    
2. Now we can create our new branch. We will use the scenario a bug ticket has been submitted with the id `SD-32` which states there is a problem with payment at checkout. So we would create our working branch by doing:

    `git checkout -b [branch_name]`
    
    where in this scenario `[branch_name]` would be `aw_SD-32_payment_at_checkout_not_working` because we are following the naming convention for a working branch (see type 4 above).
    
3. Push it to origin so you can push and pull your work for access by QA, and to later submit a pull request

    `git push -u origin [branch_name]`

### You're now ready to edit code!

#### Come back to the next section when you're done with your changes.


## Commiting your changes

So you've finished making your changes? Now you have to commit and push them so they can be passed to QA.

1. Check what files have changed

    `git status`

2. Compare the files individually to confirm the changes are correct. Make sure there are no accidental character insertions or returns

    `git diff [path_to_file]`
    
    **Hint:** You can copy and paste the `[path_to_file]` from the output of `git status`
    
3. If you're happy the changes are correct, you can add the file to the commit

    `git add [path_to_file]`
    
4. Repeat this for all the files you want to add to this commit

5. Commit your changes

    `git commit -m "[message]"`
    
    Don't forget to reference the request ID at the start of the message so it links with the request system, e.g.
    
    `git commit -m "SD-32 Fixed payment at checkout"`
    
 6. Once you've made all the changes nessecary to complete this request, you'll need to push it so you can complete Testing and QA
 
    `git push`
    
    ### Before you submit a PR
    
    Before you submit a PR, we need to rebase the branch you were working on so that it will be included at the HEAD of the parent branch, instead of attempting to merge in where you originally created the new branch.
    
    1. Make sure you're still in the branch
    
        `git branch -v`
        
        Your current branch will have an asterisk (`*`) next to it. If not you'll need to do
        
        `git checkout [branch_name]`
    
    2. Fetch the most up-to-date code for the parent repository, in this case `master`
    
        `git fetch origin master`
        
    3. Now rebase our feature with staging
    
        `git rebase master`
        
    4. Now push it to GitHub so we can create a new PR
    
        `git push -f`
    
        You'll notice the `-f`, this means force. You have to do this because rebasing rewrites the history and now git is preventing you from overwriting it. This is fine to do in this scenario because we know it will be current; only you made changes to this branch. **Never do this with a collaborative branch like staging or master though!**