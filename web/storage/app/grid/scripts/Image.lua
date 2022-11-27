local assetUrl, fileExtension, x, y, baseUrl = ...

pcall(function() game:GetService("ContentProvider"):SetBaseUrl(baseUrl) end)
game:GetService("ContentProvider"):SetThreadPool(16)
game:GetService("ScriptContext").ScriptsDisabled = true 
settings()["Task Scheduler"].ThreadPoolConfig = Enum.ThreadPoolConfig.PerCore4;

return game:GetService("ThumbnailGenerator"):ClickTexture(assetUrl, fileExtension, x, y)