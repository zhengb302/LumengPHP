#!/bin/bash
# 从远程pull当前分支

# 获取当前分支名称
branchName=$(git branch | awk '/^\*/ {print $2;}')

git pull origin $branchName
