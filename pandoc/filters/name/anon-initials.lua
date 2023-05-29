function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('name') then
  local content_string = pandoc.utils.stringify(span.content)
  local temp = ""
  for i in string.gmatch(content_string, "%S+") do
    temp = temp .. string.sub(i, 1, 1) .. ". "
  end
  temp = string.sub(temp, 1, string.len(temp)-1)
  span.content = pandoc.Str(temp)
  local value, position = span.classes:find('name')
  span.classes:remove(position)
end
return span
end